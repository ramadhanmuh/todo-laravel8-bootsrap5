@extends('layouts.admin')

@section('title', 'Administrator - Pengguna')

@section('description', 'Halaman yang menampilkan daftar pengguna yang terdaftar.')

@section('content')
    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.users.index') }}" method="GET">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="filterModalLabel">Saring Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Jenis</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Administrator" {{ $input['role'] === 'Administrator' ? 'selected' : '' }}>
                                Administrator
                            </option>
                            <option value="Owner" {{ $input['role'] === 'Owner' ? 'selected' : '' }}>
                                Owner
                            </option>
                            <option value="User" {{ $input['role'] === 'User' ? 'selected' : '' }}>
                                User
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date_created" class="form-label">Tanggal Dibuat</label>
                        <input type="date" class="form-control" id="start_date_created" data-value="{{ $input['start_date_created'] }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date_created" class="form-label">Tanggal Dibuat</label>
                        <input type="date" class="form-control" id="end_date_created" data-value="{{ $input['end_date_created'] }}">
                    </div>
                    <div>
                        <label for="keyword">Kata Kunci</label>
                        <input type="text" class="form-control" id="keyword" value="{{ $input['keyword'] }}" placeholder="ID, Nama, Username, Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h1 class="text-center mb-3 h2">Pengguna</h1>
        <div class="border-top pt-3">
            @if (session('userProcessSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('userProcessSuccessfully') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('userProcessFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('userProcessFailed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        Buat Pengguna
                    </a>
                </div>
            </div>
            <div class="row justify-content-between mb-3">
                <div class="col-auto">
                    <ul class="pagination pagination-sm m-0">
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                                <<
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                                <
                            </a>
                        </li>
                        <li class="page-item" style="max-width: 50px">
                            <form action="{{ route('admin.users.index') }}" method="get">
                                <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                                <input type="hidden" name="role" value="{{ $input['role'] }}">
                                <input type="hidden" name="start_date_created" value="{{ $input['start_date_created'] }}">
                                <input type="hidden" name="end_date_created" value="{{ $input['end_date_created'] }}">
                                <input type="text" id="page" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                            </form>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                                >
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                                >>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" type="button" data-bs-target="#filterModal">
                        Saring
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table w-100 table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Nama</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Waktu Dibuat</th>
                                    <th class="text-center">Waktu Diubah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->username }}</td>
                                        <td class="text-center">{{ $item->email }}</td>
                                        <td class="text-center">{{ $item->role }}</td>
                                        <td class="text-center unix-value" data-unix="{{ $item->created_at }}"></td>
                                        <td class="text-center unix-value" data-unix="{{ $item->updated_at }}"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Daftar pengguna tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <ul class="pagination pagination-sm m-0">
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                        <<
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                        <
                    </a>
                </li>
                <li class="page-item" style="max-width: 50px">
                    <form action="{{ route('admin.users.index') }}" method="get">
                        <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                        <input type="hidden" name="role" value="{{ $input['role'] }}">
                        <input type="hidden" name="start_date_created" value="{{ $input['start_date_created'] }}">
                        <input type="hidden" name="end_date_created" value="{{ $input['end_date_created'] }}">
                        <input type="text" id="page" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                    </form>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                        >
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('admin.users.index', $input) }}">
                        >>
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/UnixToLocal.js') }}" defer></script>
@endpush