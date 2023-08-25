@extends('layouts.admin')

@section('title', 'Administrator - Profil - Ubah')

@section('description', 'Halaman yang menampilkan formulir pengubahan profil administrator.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Ubah Profil</h2>
        <div class="border-top pt-3">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('admin.profile.update') }}" method="post" class="row">
                @csrf
                @method('PUT')
                <div class="col-lg-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ empty(old('name')) ? $user->name : old('name') }}" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ empty(old('username')) ? $user->username : old('username') }}" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ empty(old('email')) ? $user->email : old('email') }}" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="password" class="form-label">Kata Sandi Saat Ini</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-auto pe-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-light border border-dark">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection