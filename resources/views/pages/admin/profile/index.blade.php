@extends('layouts.admin')

@section('title', 'Administrator - Profil')

@section('description', 'Halaman yang menampilkan profil administrator.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Profil</h2>
        <div class="border-top pt-3">
            @if (session('profileChangedSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil mengubah profil.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary float-end mb-2">
                Ubah
            </a>
            <table class="table table-bordered">
                <tr>
                    <th class="w-25">Nama</th>
                    <td>{{ $profile->name }}</td>
                </tr>
                <tr>
                    <th class="w-25">Username</th>
                    <td>{{ $profile->username }}</td>
                </tr>
                <tr>
                    <th class="w-25">Email</th>
                    <td>{{ $profile->email }}</td>
                </tr>
                <tr>
                    <th class="w-25">Tanggal Dibuat</th>
                    <td class="unix-value" data-unix="{{ $profile->created_at }}"></td>
                </tr>
                <tr>
                    <th class="w-25">Tanggal Diubah</th>
                    <td class="unix-value" data-unix="{{ $profile->updated_at }}"></td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/UnixToLocal.js') }}"></script>
@endpush