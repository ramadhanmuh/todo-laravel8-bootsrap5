@extends('layouts.owner')

@section('title', 'Owner - Pengguna - Tambah')

@section('description', 'Halaman yang menampilkan formulir penambahan pengguna.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h1 class="text-center mb-3 h2">Tambah Pengguna</h1>
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
            <form action="{{ route('owner.users.store') }}" class="row" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" required>
                <div class="col-12 col-md-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="role" class="form-label">Jenis</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Administrator" {{ old('role') === 'Administrator' ? 'selected' : '' }}>
                            Administrator
                        </option>
                        <option value="Owner" {{ old('role') === 'Owner' ? 'selected' : '' }}>
                            Owner
                        </option>
                        <option value="User" {{ old('role') === 'User' ? 'selected' : '' }}>
                            User
                        </option>
                    </select>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary me-1" type="submit">Simpan</button>
                    <a href="{{ url()->previous() === route('owner.users.create') ? route('owner.users.index') : url()->previous() }}" class="btn btn-light border border-dark">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('id').value = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, 
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16)
        });
    </script>
@endpush