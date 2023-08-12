@extends('layouts.user')

@section('title', 'User - Beranda')

@section('description', 'Halaman yang menampilkan penyambutan pengguna.')

@section('content')
    <div class="col-11 col-md-9 col-lg-7 col-xl-6 shadow border p-3 bg-white align-self-center">
        <h1 class="text-center m-0">Selamat Datang {{ request()->get('userAuth')->name }}</h1>
    </div>
@endsection