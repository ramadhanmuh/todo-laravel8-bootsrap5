@extends('layouts.user')

@section('title', 'User - Profil')

@section('description', 'Halaman yang menampilkan profil pengguna.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Profil</h2>
        <div class="border-top pt-3">
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