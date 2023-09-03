@extends('layouts.owner')

@section('title', 'Owner - Pengguna')

@section('description', 'Halaman yang menampilkan daftar pengguna yang terdaftar.')

@section('content')
    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('owner.users.index') }}" method="GET">
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
                        <div class="row align-items-center">
                            <div class="col">
                                <input type="date" class="form-control unix-value-input" id="start_date_created" data-value="{{ $input['start_date_created'] }}">
                                <input type="hidden" name="start_date_created" value="{{ $input['start_date_created'] }}">
                            </div>
                            <div class="col-12 col-md-auto p-md-0 my-1 m-md-0 text-center">
                                <span class="d-none d-md-inline">-</span>
                                <span class="d-md-none">Sampai</span>
                            </div>
                            <div class="col">
                                <input type="date" class="form-control unix-value-input" id="end_date_created" data-value="{{ $input['end_date_created'] }}">
                                <input type="hidden" name="end_date_created" value="{{ $input['end_date_created'] }}">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="keyword">Kata Kunci</label>
                        <input type="text" name="keyword" class="form-control" id="keyword" value="{{ $input['keyword'] }}" placeholder="ID, Nama, Username, Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
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
                    <a href="{{ route('owner.users.create') }}" class="btn btn-primary">
                        Buat Pengguna
                    </a>
                </div>
            </div>
            {{-- Pagination and Filter Button --}}
            <div class="row justify-content-between mb-3">
                {{-- Pagination --}}
                <div class="col-auto">
                    <ul class="pagination pagination-sm m-0">
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                                <<
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                                <
                            </a>
                        </li>
                        <li class="page-item" style="max-width: 50px">
                            <form action="{{ route('owner.users.index') }}" method="get">
                                <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                                <input type="hidden" name="role" value="{{ $input['role'] }}">
                                <input type="hidden" class="unix-value-hidden" name="start_date_created" value="{{ $input['start_date_created'] }}">
                                <input type="hidden" class="unix-value-hidden" name="end_date_created" value="{{ $input['end_date_created'] }}">
                                <input type="text" id="page" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                            </form>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                                >
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                                >>
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- Filter Button --}}
                <div class="col-auto">
                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" type="button" data-bs-target="#filterModal">
                        Saring
                    </button>
                </div>
            </div>
            {{-- List --}}
            <div class="row">
                <div class="col-12">
                    <b>Total:</b>
                    {{ number_format($totalItems, 0, ',', '.') }}
                </div>

                {{-- List Card --}}
                @forelse ($items as $item)
                    <div class="col-12 d-md-none my-2">
                        <div class="card w-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('owner.users.show', $item->id) }}" class="text-decoration-none">{{ $item->name }}</a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">({{ $item->username }})</h6>
                                <p class="card-text">{{ $item->email }}</p>
                                <a href="{{ route('owner.users.edit', $item->id) }}" class="card-link text-decoration-none">Ubah</a>
                                <form action="{{ route('owner.users.destroy', $item->id) }}" class="card-link d-inline" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn text-danger p-0" type="submit" onclick="return confirm('Pengguna {{ $item->name }} ingin dihapus ?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <span class="text-muted d-md-none">Daftar pengguna tidak ditemukan.</span>
                @endforelse

                {{-- List Table --}}
                <div class="col-12 d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table w-100 table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">ID</th>
                                    <th>Nama</th>
                                    <th class="text-center align-middle">Username</th>
                                    <th class="text-center align-middle">Email</th>
                                    <th class="text-center align-middle">Waktu Dibuat</th>
                                    <th class="text-center align-middle"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('owner.users.show', $item->id) }}" class="text-decoration-none">{{ $item->id }}</a>
                                        </td>
                                        <td class="align-middle">
                                            {{ $item->name }}
                                        </td>
                                        <td class="text-center align-middle">{{ $item->username }}</td>
                                        <td class="text-center align-middle">{{ $item->email }}</td>
                                        <td class="text-center align-middle unix-value" data-unix="{{ $item->created_at }}"></td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group dropstart">
                                                <button type="button" class="btn btn-light border border-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('owner.users.show', $item->id) }}" class="dropdown-item">
                                                            Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('owner.users.edit', $item->id) }}" class="dropdown-item">
                                                            Ubah
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('owner.users.destroy', $item->id) }}" class="dropdown-item" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-100 btn text-start p-0" onclick="return confirm('Pengguna {{ $item->name }} ingin dihapus ?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted text-center">Daftar pengguna tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Pagination --}}
            <ul class="pagination pagination-sm m-0">
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                        <<
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                        <
                    </a>
                </li>
                <li class="page-item" style="max-width: 50px">
                    <form action="{{ route('owner.users.index') }}" method="get">
                        <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                        <input type="hidden" name="role" value="{{ $input['role'] }}">
                        <input type="hidden" class="unix-value-hidden" name="start_date_created" value="{{ $input['start_date_created'] }}">
                        <input type="hidden" class="unix-value-hidden" name="end_date_created" value="{{ $input['end_date_created'] }}">
                        <input type="text" id="page" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                    </form>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                        >
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('owner.users.index', $input) }}">
                        >>
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/UnixToLocal.js') }}" defer></script>
    <script src="{{ url('assets/js/admin/users/list.js') }}" defer></script>
@endpush