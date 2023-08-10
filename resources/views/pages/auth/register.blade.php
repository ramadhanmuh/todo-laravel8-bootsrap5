@extends('layouts.auth')

@section('title', 'Pendaftaran')

@section('description', 'Halaman untuk mendaftar akun aplikasi ToDo.')

@section('content')
    <div class="col-11 col-md-9 col-lg-7 col-xl-6 shadow border p-3 bg-white">
        <h1 class="text-center mb-3">Pendaftaran</h1>
        <div class="alert alert-danger d-none" id="validationErrorMessageColumn">
            <ul class="m-0" id="validationErrorMessageList">
            </ul>
        </div>
        <div class="d-none alert alert-success" id="successAlertForm"></div>
        <form class="row" method="POST" action="{{ route('register.save') }}" id="registerForm">
            <input type="hidden" name="id">
            <div class="col-12 mb-3">
                <label for="name" class="form-label">
                    Nama
                </label>
                <input type="text" class="form-control" name="name" maxlength="255" value="{{ old('name') }}" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="username" class="form-label">
                    Username
                </label>
                <input type="text" class="form-control" name="username" maxlength="191" value="{{ old('username') }}" required>
            </div>
    
            <div class="col-12 col-md-6 mb-3">
                <label for="email" class="form-label">
                    Email
                </label>
                <input type="email" class="form-control" name="email" maxlength="191" value="{{ old('email') }}" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="password" class="form-label">
                    Kata Sandi
                </label>
                <input type="password" class="form-control" name="password" maxlength="255" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="password" class="form-label">
                    Konfirmasi Kata Sandi
                </label>
                <input type="password" class="form-control" name="password_confirmation" maxlength="255" required>
            </div>
            <div class="col-12">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-primary" type="submit" disabled id="submitButton">
                            Daftar
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ url('/') }}" class="text-decoration-none" id="toLoginPageButton">
                            Halaman Masuk
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/register.js') }}"></script>
@endpush