@extends('layouts.admin')

@section('title', 'Administrator - Aplikasi')

@section('description', 'Halaman yang menampilkan informasi aplikasi.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Aplikasi</h2>
        <div class="border-top pt-3">
            @if (session('applicationChangedSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil mengubah informasi aplikasi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.application.edit') }}" class="btn btn-secondary mb-2">
                    Ubah
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered w-100">
                    <tr>
                        <th class="w-25">Nama</th>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Slogan</th>
                        <td>{{ $item->tagline }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Deskripsi</th>
                        <td>{{ $item->description }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Copyright</th>
                        <td>{{ $item->copyright }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Pengisi Informasi</th>
                        <td>{{ empty($item->user_name) ? '' : $item->user_name . ' (' . $item->username . ')' }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Tanggal Dibuat</th>
                        <td class="unix-value" data-unix="{{ $item->created_at }}"></td>
                    </tr>
                    <tr>
                        <th class="w-25">Tanggal Diubah</th>
                        <td class="unix-value" data-unix="{{ $item->updated_at }}"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/UnixToLocal.js') }}"></script>
@endpush