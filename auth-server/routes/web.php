<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1')
    ->name('login.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/map', fn () => view('map'))->name('map');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/map-assets/{asset}', function (string $asset) {
        $assets = [
            'style.css' => 'css/style.css',
            'bataskabupaten.js' => 'js/bataskabupaten.js',
            'LBSBogor.js' => 'js/LBSBogor.js',
            'LSDBogor.js' => 'js/LSDBogor.js',
            'Jalan_bogor.json' => 'geojson/Jalan_bogor.json',
            'Kecamatan_Clip.json' => 'geojson/Kecamatan_Clip.json',
            'main.js' => 'js/main.js',
        ];

        abort_unless(isset($assets[$asset]), 404);

        $path = dirname(base_path()).DIRECTORY_SEPARATOR
            .str_replace('/', DIRECTORY_SEPARATOR, $assets[$asset]);
        abort_unless(is_file($path), 404);

        $contentType = match (pathinfo($asset, PATHINFO_EXTENSION)) {
            'css' => 'text/css; charset=UTF-8',
            'js' => 'text/javascript; charset=UTF-8',
            'json' => 'application/geo+json',
            default => 'application/octet-stream',
        };

        return response()->file($path, ['Content-Type' => $contentType]);
    })->whereIn('asset', [
        'style.css',
        'bataskabupaten.js',
        'LBSBogor.js',
        'LSDBogor.js',
        'Jalan_bogor.json',
        'Kecamatan_Clip.json',
        'main.js',
    ])->name('map.asset');
});
