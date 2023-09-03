@extends('layouts.owner')

@section('title', 'Owner - Pengguna - Tambah')

@section('description', 'Halaman yang menampilkan formulir penambahan pengguna.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h1 class="text-center h2 m-0">Ubah Pengguna</h1>
        <h5 class="text-center mb-3 h5">({{ $item->name }})</h5>
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
            <form action="{{ route('owner.users.update', $item->id) }}" class="row" method="POST">
                @csrf
                @method('PUT')
                <div class="col-12 col-md-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ empty(old('name')) ? $item->name : old('name') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ empty(old('username')) ? $item->username : old('username') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ empty(old('email')) ? $item->email : old('email') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="role" class="form-label">Jenis</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Administrator" {{ (empty(old('role')) && $item->role === 'Administrator') || old('role') === 'Administrator' ? 'selected' : '' }}>
                            Administrator
                        </option>
                        <option value="Owner" {{ (empty(old('role')) && $item->role === 'Owner') || old('role') === 'Owner' ? 'selected' : '' }}>
                            Owner
                        </option>
                        <option value="User" {{ (empty(old('role')) && $item->role === 'User') || old('role') === 'User' ? 'selected' : '' }}>
                            User
                        </option>
                    </select>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="password" class="form-label">Kata Sandi (Opsional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi (Opsional)</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary me-1" type="submit">Simpan</button>
                    <a href="{{ $backURL }}" class="btn btn-light border border-dark">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush