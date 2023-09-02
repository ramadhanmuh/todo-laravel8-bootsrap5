@extends('layouts.auth')

@section('title', 'Owner - Atur Ulang Kata Sandi')

@section('description', 'Halaman yang menampilkan formulir atur ulang kata sandi untuk owner.')

@section('content')
    <div class="col-11 col-md-6 col-lg-5 shadow border p-3 bg-white">
        <h1 class="text-center m-0">Atur Ulang Kata Sandi</h1>
        <h5 class="text-center mb-3">(Owner)</h5>
        <div class="alert alert-danger d-none" id="validationErrorMessageColumn">
            <ul class="m-0" id="validationErrorMessageList">
            </ul>
        </div>
        <div class="d-none alert alert-success" id="successAlertForm"></div>
        <form class="row" method="POST" action="{{ route('owner.reset-password.save') }}" id="resetPasswordForm">
            <div class="col-12 mb-3">
                <label for="password" class="form-label">
                    Kata Sandi
                </label>
                <input type="password" class="form-control" id="password" name="password" maxlength="255" required>
            </div>
            <div class="col-12 mb-3">
                <label for="password_confirmation" class="form-label">
                    Konfirmasi Kata Sandi
                </label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" maxlength="255" required>
            </div>
            <div class="col-12">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-primary" type="submit" disabled id="submitButton">
                            Ubah Kata Sandi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/owner/reset-password.js') }}"></script>
@endpush