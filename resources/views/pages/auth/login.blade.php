@extends('layouts.auth')

@section('title', 'Login')

@section('description', 'Halaman yang menampilkan formulir login.')

@section('content')
    <div class="col-11 col-md-6 col-lg-5 col-xl-4 shadow border p-3 bg-white">
        <h1 class="text-center mb-3">Login</h1>
        <div class="alert alert-danger d-none" id="validationErrorMessageColumn">
            <ul class="m-0" id="validationErrorMessageList">
            </ul>
        </div>
        <div class="d-none alert alert-success" id="successAlertForm"></div>
        <form class="row" method="POST" action="{{ route('login.authenticate') }}" id="loginForm">
            <input type="hidden" name="id">
            <div class="col-12 mb-3">
                <label for="identity" class="form-label">
                    Username / Email
                </label>
                <input type="text" class="form-control" id="identity" name="identity" maxlength="255" required>
            </div>
            <div class="col-12 mb-3">
                <label for="password" class="form-label">
                    Kata Sandi
                </label>
                <input type="password" class="form-control" id="password" name="password" maxlength="255" required>
            </div>
            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Yes" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Selalu Login
                    </label>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-primary" type="submit" disabled id="submitButton">
                            Login
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('register.show') }}" class="text-decoration-none" id="toLoginPageButton">
                            Halaman Daftar
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <a href="{{ url('forgot-password') }}" class="text-decoration-none">
                    Lupa Kata Sandi ?
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/register.js') }}"></script>
@endpush