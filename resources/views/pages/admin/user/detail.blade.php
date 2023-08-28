@extends('layouts.admin')

@section('title', 'Administrator - Pengguna')

@section('description', 'Halaman yang menampilkan daftar pengguna yang terdaftar.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h1 class="text-center mb-3 h2">Pengguna ({{ $item->name }})</h1>
        <div class="border-top pt-3">
            <div class="table-responsive">
                <table class="table w-100 table-bordered">
                    <tr>
                        <th class="w-25">Username</th>
                        <td>{{ $item->username }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Email</th>
                        <td>{{ $item->email }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Jenis</th>
                        <td>{{ $item->role }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Waktu Terverifikasi</th>
                        <td class="unix-value" data-unix="{{ $item->email_verified_at }}"></td>
                    </tr>
                    <tr>
                        <th class="w-25">Waktu Dibuat</th>
                        <td class="unix-value" data-unix="{{ $item->created_at }}"></td>
                    </tr>
                    <tr>
                        <th class="w-25">Waktu Diubah</th>
                        <td class="unix-value" data-unix="{{ $item->updated_at }}"></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col-auto">
                    <a href="{{ route('admin.users.edit', $item->id) }}" class="btn btn-outline-primary">Ubah</a>
                </div>
                <div class="col-auto">
                    <a href="{{ url()->previous() }}" class="btn btn-light border border-black">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/UnixToLocal.js') }}" defer></script>
@endpush