$PHP_BIN = 'C:\laragon\bin\php\php-8.3.36-Win32-vs16-x64\php.exe'
Set-Location $PSScriptRoot

if (-not (Test-Path $PHP_BIN)) {
    Write-Error "PHP Laragon tidak ditemukan di: $PHP_BIN"
    exit 1
}

& $PHP_BIN artisan serve --host=127.0.0.1 --port=8000
