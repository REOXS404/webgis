{{-- Mendeklarasikan dokumen sebagai HTML5. --}}
<!doctype html>
{{-- Membuka dokumen HTML dan menetapkan bahasa utama ke Bahasa Indonesia. --}}
<html lang="id">
{{-- Membuka bagian metadata dan referensi aset halaman. --}}
<head>
    {{-- Menetapkan encoding karakter agar teks Indonesia ditampilkan dengan benar. --}}
    <meta charset="utf-8">
    {{-- Mengatur viewport agar tata letak menyesuaikan lebar perangkat. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Menetapkan judul yang ditampilkan pada tab browser. --}}
    <title>Peta WebGIS LP2B Kabupaten Bogor</title>
    {{-- Memuat stylesheet aplikasi melalui rute aset dengan parameter versi cache. --}}
    <link rel="stylesheet" href="{{ route('map.asset', ['asset' => 'style.css', 'v' => '8']) }}">
    {{-- Memuat stylesheet Leaflet versi 1.9.4 dari CDN. --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        {{-- Memverifikasi integritas file CSS CDN dan mengizinkan permintaan lintas origin tanpa kredensial. --}}
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    {{-- Memulai aturan CSS khusus tampilan halaman peta. --}}
    <style>
        /* Menyusun isi halaman secara vertikal dan memenuhi tinggi viewport. */
        body { display: flex; flex-direction: column; height: 100vh; }
        /* Mengatur tata letak dan warna area header peta. */
        .map-header {
            display: flex; /* Menyusun isi header dengan model flexbox. */
            min-height: 68px; /* Menetapkan tinggi minimum header. */
            align-items: center; /* Menyelaraskan isi header secara vertikal ke tengah. */
            justify-content: space-between; /* Memisahkan judul dan area akun ke kedua sisi. */
            gap: 16px; /* Memberi jarak antar elemen header. */
            padding: 10px 24px; /* Memberi ruang di dalam header. */
            color: #fff; /* Mengatur warna teks header menjadi putih. */
            background: #205e3d; /* Memberi warna latar hijau pada header. */
        }
        /* Mengatur jarak dan ukuran judul utama peta. */
        .map-title { margin: 0; font-size: 17px; }
        /* Mengatur jarak, warna, dan ukuran teks keterangan peta. */
        .map-subtitle { margin: 4px 0 0; color: #d3e6d7; font-size: 12px; }
        /* Menyusun nama akun dan tombol keluar dalam satu baris. */
        .map-account { display: flex; align-items: center; gap: 14px; font-size: 13px; }
        /* Mengatur tampilan tombol dalam area akun. */
        .map-account button {
            padding: 8px 12px; /* Memberi ruang di dalam tombol. */
            border: 1px solid #ffffff80; /* Memberi garis tepi putih semi-transparan. */
            border-radius: 7px; /* Membulatkan sudut tombol. */
            color: #fff; /* Mengatur warna teks tombol menjadi putih. */
            background: transparent; /* Membuat latar tombol transparan. */
            cursor: pointer; /* Menampilkan kursor tangan saat tombol diarahkan. */
            font: inherit; /* Menggunakan jenis dan ukuran font dari elemen induk. */
        }
        /* Memberi perubahan latar saat pointer diarahkan ke tombol akun. */
        .map-account button:hover { background: #ffffff20; }
        /* Membuat area peta selebar halaman dan mengisi sisa ruang vertikal. */
        #map { width: 100%; min-height: 0; flex: 1; }
        /* Menerapkan penyesuaian tata letak ketika lebar layar maksimal 600 piksel. */
        @media (max-width: 600px) {
            /* Mengurangi jarak sisi header pada layar kecil. */
            .map-header { padding: 10px 14px; }
            /* Mengecilkan judul agar lebih sesuai dengan layar kecil. */
            .map-title { font-size: 14px; }
            /* Mengurangi jarak dan ukuran teks pada area akun untuk layar kecil. */
            .map-account { gap: 8px; font-size: 12px; }
        }
    {{-- Mengakhiri aturan CSS internal halaman. --}}
    </style>
{{-- Mengakhiri metadata dan aset halaman. --}}
</head>
{{-- Membuka bagian isi dokumen yang ditampilkan kepada pengguna. --}}
<body>
    {{-- Membuat header yang menampilkan identitas aplikasi dan akun aktif. --}}
    <header class="map-header">
        {{-- Mengelompokkan judul dan keterangan aplikasi. --}}
        <div>
            {{-- Menampilkan judul utama WebGIS. --}}
            <h1 class="map-title">WebGIS LP2B Kabupaten Bogor</h1>
            {{-- Menjelaskan kepanjangan/topik data yang ditampilkan pada peta. --}}
            <p class="map-subtitle">Lahan Pertanian Pangan Berkelanjutan</p>
        {{-- Menutup kelompok judul dan keterangan. --}}
        </div>
        {{-- Mengelompokkan nama akun dan kontrol untuk keluar. --}}
        <div class="map-account">
            {{-- Menampilkan nama pengguna yang sedang masuk. --}}
            <span>{{ auth()->user()->name }}</span>
            {{-- Membuat formulir POST untuk mengirim permintaan keluar ke rute logout. --}}
            <form method="POST" action="{{ route('logout') }}">
                {{-- Menambahkan token CSRF untuk melindungi pengiriman formulir keluar. --}}
                @csrf
                {{-- Membuat tombol yang mengirimkan formulir logout. --}}
                <button type="submit">Keluar</button>
            {{-- Menutup formulir logout. --}}
            </form>
        {{-- Menutup kelompok informasi akun dan tombol keluar. --}}
        </div>
    {{-- Menutup header halaman peta. --}}
    </header>
    {{-- Menyediakan wadah peta Leaflet dan label aksesibilitas untuk pembaca layar. --}}
    <div id="map" aria-label="Peta interaktif Kabupaten Bogor"></div>
    {{-- Memuat pustaka JavaScript Leaflet versi 1.9.4 dari CDN. --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        {{-- Memverifikasi integritas file JavaScript CDN dan mengizinkan permintaan lintas origin tanpa kredensial. --}}
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- Memuat data batas kabupaten untuk digunakan oleh kode peta. --}}
    <script src="{{ route('map.asset', ['asset' => 'bataskabupaten.js']) }}"></script>
    {{-- Memuat data Lahan Baku Sawah untuk digunakan oleh kode peta. --}}
    <script src="{{ route('map.asset', ['asset' => 'LBSBogor.js']) }}"></script>
    {{-- Memuat data Lahan Sawah Dilindungi untuk digunakan oleh kode peta. --}}
    <script src="{{ route('map.asset', ['asset' => 'LSDBogor.js']) }}"></script>
    {{-- Memulai skrip konfigurasi URL sumber data GeoJSON. --}}
    <script>
        // Membuat konfigurasi global yang dapat dibaca oleh skrip peta utama.
        window.webgisDataUrls = {
            // Menetapkan URL aset data jalan menggunakan generator rute Laravel.
            jalan: @json(route('map.asset', ['asset' => 'Jalan_bogor.json'])),
            // Menetapkan URL aset data kecamatan menggunakan generator rute Laravel.
            kecamatan: @json(route('map.asset', ['asset' => 'Kecamatan_Clip.json']))
        };
    {{-- Mengakhiri skrip konfigurasi URL data. --}}
    </script>
    {{-- Memuat skrip utama untuk membuat peta dan menampilkan layer. --}}
    <script src="{{ route('map.asset', ['asset' => 'main.js', 'v' => '8']) }}"></script>
{{-- Mengakhiri isi dokumen. --}}
</body>
{{-- Menutup dokumen HTML. --}}
</html>
