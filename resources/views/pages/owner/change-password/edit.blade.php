@extends('layouts.owner')

@section('title', 'Owner - Ubah Kata Sandi')

@section('description', 'Halaman yang menampilkan formulir pengubahan kata sandi owner.')

@section('content')
    <div class="col-11 col-md-7 col-lg-6 col-xl-5 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Ubah Kata Sandi</h2>
        <div class="border-top pt-3">
            @if (session('passwordChangedSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil mengubah kata sandi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
            <form action="{{ route('owner.change-password.update') }}" method="post" class="row">
                @csrf
                @method('PUT')
                <div class="col-12 mb-3">
                    <label for="old_password" class="form-label">Kata Sandi Lama</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="col-auto pe-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection