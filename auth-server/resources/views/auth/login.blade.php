{{-- Menggunakan kerangka tampilan autentikasi sebagai layout halaman ini. --}}
@extends('layouts.auth')

{{-- Menetapkan judul halaman yang dapat digunakan oleh layout. --}}
@section('title', 'Masuk')

{{-- Memulai bagian konten yang akan ditempatkan oleh layout. --}}
@section('content')
    {{-- Menampilkan sapaan utama pada halaman masuk. --}}
    <h1>Selamat datang</h1>
    {{-- Menjelaskan tujuan masuk ke aplikasi peta. --}}
    <p class="intro">Masuk untuk melihat peta Lahan Pertanian Pangan Berkelanjutan Kabupaten Bogor.</p>

    {{-- Memeriksa apakah ada pesan kesalahan validasi pada formulir. --}}
    @if ($errors->any())
        {{-- Menampilkan pesan kesalahan validasi pertama dengan peran aksesibilitas alert. --}}
        <div class="form-error" role="alert">{{ $errors->first() }}</div>
    {{-- Mengakhiri pemeriksaan kesalahan validasi. --}}
    @endif
    {{-- Memeriksa apakah ada pesan status dari sesi, misalnya setelah proses sebelumnya. --}}
    @if (session('status'))
        {{-- Menampilkan pesan status sebagai notifikasi. --}}
        <div class="form-error" role="alert">{{ session('status') }}</div>
    {{-- Mengakhiri pemeriksaan pesan status. --}}
    @endif

    {{-- Membuat formulir POST yang mengirimkan data ke rute penyimpanan login. --}}
    <form method="POST" action="{{ route('login.store') }}">
        {{-- Menambahkan token CSRF untuk melindungi pengiriman formulir. --}}
        @csrf
        {{-- Membungkus field email agar dapat ditata bersama melalui CSS. --}}
        <div class="field">
            {{-- Menghubungkan label dengan input email melalui atribut for/id. --}}
            <label for="email">Email</label>
            {{-- Menerima alamat email, mengisi kembali nilai sebelumnya, dan mengaktifkan validasi serta fokus awal. --}}
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            {{-- Menampilkan pesan validasi khusus email jika tersedia. --}}
            @error('email') <p class="error">{{ $message }}</p> @enderror
        {{-- Menutup pembungkus field email. --}}
        </div>

        {{-- Membungkus field kata sandi agar dapat ditata bersama melalui CSS. --}}
        <div class="field">
            {{-- Menghubungkan label dengan input kata sandi melalui atribut for/id. --}}
            <label for="password">Kata sandi</label>
            {{-- Menerima kata sandi dan memberi petunjuk autocomplete untuk kata sandi akun saat ini. --}}
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            {{-- Menampilkan pesan validasi khusus kata sandi jika tersedia. --}}
            @error('password') <p class="error">{{ $message }}</p> @enderror
        {{-- Menutup pembungkus field kata sandi. --}}
        </div>

        {{-- Membuat label untuk opsi mengingat sesi masuk pengguna. --}}
        <label class="remember">
            {{-- Mengirim nilai remember=1 jika kotak centang dipilih. --}}
            <input type="checkbox" name="remember" value="1">
            {{-- Menampilkan teks opsi kotak centang. --}}
            Ingat saya
        {{-- Menutup label opsi ingat saya. --}}
        </label>

        {{-- Mengirim formulir untuk memulai proses masuk. --}}
        <button type="submit">Masuk ke peta</button>
    {{-- Menutup formulir masuk. --}}
    </form>

    {{-- Menawarkan tautan ke halaman pendaftaran bagi pengguna yang belum memiliki akun. --}}
    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
{{-- Mengakhiri bagian konten halaman. --}}
@endsection
