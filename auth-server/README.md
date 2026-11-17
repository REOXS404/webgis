# WebGIS LP2B Kabupaten Bogor

Aplikasi Laravel ini menyediakan registrasi, login berbasis session, dan halaman peta yang hanya bisa dibuka setelah login. Data pengguna disimpan di MySQL. Node.js tidak diperlukan untuk menjalankan autentikasi ini.

## Menjalankan di Laragon

1. Jalankan Laragon dan nyalakan **MySQL**.
2. Buka `http://localhost/phpmyadmin`, buat database bernama `webgis_auth` dengan collation `utf8mb4_unicode_ci`.
3. Periksa pengaturan database di `.env`. Konfigurasi awal memakai `127.0.0.1:3306`, pengguna `root`, dan password kosong (sesuaikan jika Laragon Anda berbeda).
4. Buka PowerShell di folder `C:\laragon\www\webgis\auth-server`. Pada instalasi pertama, buat encryption key jika `APP_KEY` di `.env` masih kosong, lalu jalankan migrasi:

   ```powershell
   php artisan key:generate
   php artisan migrate
   ```

   Selanjutnya jalankan server:

   ```powershell
   php artisan serve --host=localhost --port=8000
   ```

5. Kunjungi `http://localhost:8000`, pilih **Daftar** untuk membuat akun pertama, lalu masuk.

Route halaman peta dan asset JavaScript/GeoJSON dilindungi middleware login Laravel. File di folder `css`, `js`, dan `geojson` juga menolak akses langsung melalui Apache. Tile peta memerlukan koneksi internet.

## Pengujian

```powershell
php artisan test
```
