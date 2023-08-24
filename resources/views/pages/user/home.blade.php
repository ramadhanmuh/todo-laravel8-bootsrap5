@extends('layouts.user')

@section('title', 'User - Beranda')

@section('description', 'Halaman yang menampilkan penyambutan pengguna.')

@section('content')
    <div class="col-11 col-md-9 col-lg-7 col-xl-6 shadow border p-3 bg-white align-self-center">
        <h1 class="text-center">Selamat Datang {{ request()->get('userAuth')->name }}</h1>
        <div class="text-center">
            <a href="{{ route('user.tasks.index') }}" class="btn btn-primary">
                Ke Halaman Daftar Tugas
            </a>
        </div>
    </div>
@endsection