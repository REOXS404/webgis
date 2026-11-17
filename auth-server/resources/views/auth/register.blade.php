{{-- Menggunakan kerangka tampilan autentikasi sebagai layout halaman ini. --}}
@extends('layouts.auth')

{{-- Menetapkan judul halaman yang dapat digunakan oleh layout. --}}
@section('title', 'Daftar')

{{-- Memulai bagian konten yang akan ditempatkan oleh layout. --}}
@section('content')
    {{-- Menampilkan judul utama halaman pendaftaran. --}}
    <h1>Buat akun</h1>
    {{-- Menjelaskan tujuan pembuatan akun untuk mengakses peta. --}}
    <p class="intro">Daftar untuk mengakses peta Lahan Pertanian Pangan Berkelanjutan Kabupaten Bogor.</p>

    {{-- Memeriksa apakah terdapat kesalahan validasi pada data pendaftaran. --}}
    @if ($errors->any())
        {{-- Menampilkan kesalahan validasi pertama sebagai notifikasi. --}}
        <div class="form-error" role="alert">{{ $errors->first() }}</div>
    {{-- Mengakhiri pemeriksaan kesalahan validasi. --}}
    @endif

    {{-- Membuat formulir POST yang mengirimkan data ke rute penyimpanan pendaftaran. --}}
    <form method="POST" action="{{ route('register.store') }}">
        {{-- Menambahkan token CSRF untuk melindungi pengiriman formulir. --}}
        @csrf
        {{-- Membungkus field nama agar dapat ditata bersama melalui CSS. --}}
        <div class="field">
            {{-- Menghubungkan label nama dengan input yang memiliki ID name. --}}
            <label for="name">Nama</label>
            {{-- Menerima nama, mengisi kembali nilai sebelumnya, dan mengaktifkan autocomplete serta fokus awal. --}}
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
            {{-- Menampilkan pesan validasi nama jika tersedia. --}}
            @error('name') <p class="error">{{ $message }}</p> @enderror
        {{-- Menutup pembungkus field nama. --}}
        </div>

        {{-- Membungkus field email agar dapat ditata bersama melalui CSS. --}}
        <div class="field">
            {{-- Menghubungkan label email dengan input yang memiliki ID email. --}}
            <label for="email">Email</label>
            {{-- Menerima alamat email, mengisi kembali nilai sebelumnya, dan mengaktifkan validasi serta autocomplete. --}}
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            {{-- Menampilkan pesan validasi email jika tersedia. --}}
            @error('email') <p class="error">{{ $message }}</p> @enderror
        {{-- Menutup pembungkus field email. --}}
        </div>

        {{-- Membungkus field kata sandi agar dapat ditata bersama melalui CSS. --}}
        <div class="field">
            {{-- Menjelaskan bahwa kata sandi minimal memiliki delapan karakter. --}}
            <label for="password">Kata sandi (minimal 8 karakter)</label>
            {{-- Menerima kata sandi dan memberi petunjuk browser untuk membuat kata sandi baru. --}}
            <input id="password" name="password" type="password" autocomplete="new-password" required>
            {{-- Menampilkan pesan validasi kata sandi jika tersedia. --}}
            @error('password') <p class="error">{{ $message }}</p> @enderror
        {{-- Menutup pembungkus field kata sandi. --}}
        </div>

        {{-- Membungkus field untuk mengonfirmasi kata sandi. --}}
        <div class="field">
            {{-- Menghubungkan label konfirmasi dengan input yang memiliki ID password_confirmation. --}}
            <label for="password_confirmation">Ulangi kata sandi</label>
            {{-- Meminta pengguna memasukkan ulang kata sandi yang sama menggunakan autocomplete kata sandi baru. --}}
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        {{-- Menutup pembungkus field konfirmasi kata sandi. --}}
        </div>

        {{-- Mengirim formulir untuk memproses pembuatan akun. --}}
        <button type="submit">Buat akun</button>
    {{-- Menutup formulir pendaftaran. --}}
    </form>

    {{-- Menawarkan tautan ke halaman masuk bagi pengguna yang sudah memiliki akun. --}}
    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
{{-- Mengakhiri bagian konten halaman. --}}
@endsection
