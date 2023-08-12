@extends('layouts.auth')

@section('title', 'Verifikasi Berhasil')

@section('description', 'Halaman untuk informasi keberhasilan verifikasi akun.')

@section('content')
    <div class="col-11 col-md-7 shadow border p-3 bg-white text-center">
        <h1 class="text-center mb-3">Verifikasi Berhasil</h1>
        <p class="m-0">Akun telah diaktifkan oleh aplikasi. Silahkan tekan tombol dibawah ini untuk ke halaman Login.</p>
        <a href="{{ url('login') }}" class="btn btn-primary mt-2">
            Login
        </a>
    </div>
@endsection