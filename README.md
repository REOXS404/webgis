# WebGIS LP2B Kabupaten Bogor

Dokumentasi untuk aplikasi WebGIS Kabupaten Bogor yang menampilkan peta, batas kecamatan, jalan, dan lahan sawah. Aplikasi ini terdiri dari frontend WebGIS statis dan server autentikasi Laravel yang berada di folder `auth-server`.

## 1. Ringkasan

Aplikasi ini menyediakan:
- Peta interaktif berbasis Leaflet
- Layer batas kabupaten, kecamatan, jalan, dan lahan sawah
- Legenda peta
- Pengaturan basis map: OSM, Satellite, dan Topographic
- Sistem login/registrasi menggunakan Laravel di folder `auth-server`

## 2. Struktur Project

```text
webgis/
├── index.html              # halaman utama WebGIS
├── css/
│   └── style.css           # styling peta dan legenda
├── geojson/
│   ├── Jalan_bogor.json
│   ├── Kecamatan_Clip.json
│   ├── LBSBogor.json
│   └── LSDBogor.json
├── js/
│   ├── main.js             # inisialisasi peta dan layer
│   ├── bataskabupaten.js
│   ├── LBSBogor.js
│   └── LSDBogor.js
├── libs/                   # library pendukung jika ada
├── auth-server/            # aplikasi Laravel untuk autentikasi
│   ├── app/
│   ├── resources/
│   ├── routes/
│   ├── .env
│   ├── artisan
│   └── README.md
├── README.md               # dokumentasi project
└── .htaccess / konfigurasi server (jika dipakai)
```

## 3. Persyaratan

- PHP 8.3 atau versi yang kompatibel
- Composer
- MySQL (untuk autentikasi Laravel)
- Browser modern (Chrome/Edge/Firefox)
- Koneksi internet untuk memuat tile peta dari OpenStreetMap/Google

## 4. Menjalankan Aplikasi

### 4.1 Menjalankan WebGIS tanpa autentikasi

Jika Anda hanya ingin menampilkan map statis dari folder root proyek:

```powershell
cd C:\laragon\www\webgis
php -S localhost:8080 -t .
```

Mode ini **tidak menggunakan autentikasi**. Buka di browser:

```text
http://localhost:8080/
```

### 4.2 Menjalankan versi dengan autentikasi Laravel

Aplikasi autentikasi berada di folder `auth-server` dan mengamankan akses ke halaman peta.

Langkah-langkah:

1. Pastikan MySQL sudah aktif di Laragon.
2. Buat database baru dengan nama:

```text
webgis_auth
```

3. Buka folder proyek auth:

```powershell
cd C:\laragon\www\webgis\auth-server
```

4. Pada instalasi pertama, buat encryption key jika `APP_KEY` di `.env` masih kosong, lalu jalankan migrasi:

```powershell
php artisan key:generate
php artisan migrate
```

5. Jalankan server Laravel di port 8000 (port ini khusus untuk server autentikasi):

```powershell
php artisan serve --host=localhost --port=8000
```

6. Buka alamat berikut di browser:

```text
http://localhost:8000
```

7. Pilih menu **Daftar** untuk membuat akun pertama, lalu login.

## 5. Fitur Layer Peta

Aplikasi menampilkan layer berikut:

- Kabupaten: batas wilayah administratif
- Kecamatan: pembagian wilayah kecamatan
- Jalan Bogor: jaringan jalan
- Lahan Baku Sawah (LBS)
- Lahan Sawah Dilindungi (LSD)

## 6. Detail Teknis

### Frontend
- Leaflet JS
- GeoJSON data
- URL data diset pada halaman `index.html`

### Backend Auth
- Laravel
- Session-based authentication
- MySQL sebagai penyimpanan data pengguna

## 7. File Utama

- `index.html` — entry point aplikasi
- `js/main.js` — inisialisasi peta, layer, legend, dan kontrol map
- `css/style.css` — layout dan styling legend serta peta
- `geojson/*.json` — data spasial untuk peta
- `auth-server/.env` — konfigurasi database dan aplikasi Laravel

## 8. Troubleshooting

### Masalah: Halaman tidak tampil
- Pastikan server lokal berjalan.
- Cek apakah file `index.html` terload dengan benar.
- Pastikan path file GeoJSON benar, misalnya:

```text
geojson/Jalan_bogor.json
geojson/Kecamatan_Clip.json
```

### Masalah: Data tidak muncul
- Pastikan koneksi internet aktif untuk memuat tile peta.
- Cek console browser untuk melihat error JavaScript.
- Cek apakah GeoJSON valid dan tidak rusak.

### Masalah: Login gagal
- Pastikan database MySQL aktif.
- Pastikan server yang dijalankan berasal dari folder `auth-server`, bukan server PHP statis dari folder root.
- Pastikan `APP_KEY` di `.env` sudah diisi dan database di `.env` sudah benar.
- Jika skema database belum tersedia, jalankan migrasi:

```powershell
cd C:\laragon\www\webgis\auth-server
php artisan migrate
```

## 9. Catatan Pengembangan

- Untuk pengujian Laravel:

```powershell
cd C:\laragon\www\webgis\auth-server
php artisan test
```

- Jika Anda ingin mengubah layer atau styling peta, lakukan perubahan pada file di folder `js/` dan `css/` sesuai kebutuhan.

## 10. Kesimpulan

Proyek WebGIS LP2B Kabupaten Bogor merupakan aplikasi peta interaktif yang memadukan data spasial GeoJSON dengan framework Laravel untuk autentikasi dan keamanan akses. Dengan struktur project yang terpisah antara frontend dan backend, pengembangan dan maintenance aplikasi menjadi lebih terorganisir.
