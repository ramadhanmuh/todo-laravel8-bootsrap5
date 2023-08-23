@extends('layouts.user')

@section('title', 'User - Tugas')

@section('description', 'Halaman yang menampilkan daftar tugas pengguna.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Tugas</h2>
        <div class="border-top pt-3">
            @if (session('taskProcessSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('taskProcessSuccessfully') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('taskProcessFailed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('taskProcessFailed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('user.tasks.create') }}" class="btn btn-primary">
                    Tambah
                </a>
            </div>
            {{-- Filter --}}
            <form class="row" action="{{ route('user.tasks.index') }}">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="row">
                        <div class="col col-md col-lg-3">
                            <div class="row align-items-center">
                                <div class="col-auto pe-0">
                                    <label for="year" class="col-form-label fw-bold">Tahun</label>
                                </div>
                                <div class="col-12 col-md">
                                    <input type="text" class="form-control form-control-sm" id="year" name="year" placeholder="****" value="{{ $input['year'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-3">
                            <div class="row align-items-center">
                                <div class="col-auto pe-0">
                                    <label for="month" class="col-form-label fw-bold">Bulan</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="form-control form-control-sm" id="month" name="month" placeholder="**" value="{{ $input['month'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col col-lg-3 ps-0">
                            <div class="row align-items-center">
                                <div class="col-auto col-md-6 pe-0">
                                    <label for="day" class="col-form-label fw-bold">Tanggal</label>
                                </div>
                                <div class="col-12 col-md-6 ">
                                    <input type="text" class="form-control form-control-sm" id="day" name="day" placeholder="**" value="{{ $input['day'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto align-self-end align-self-md-center">
                            <button class="btn btn-primary btn-sm" type="submit">Saring</button>
                        </div>
                    </div>
                </div>
            </form>
            {{-- Pagination --}}
            <div class="row align-items-center mt-4">
                <div class="col-md-auto pe-0 mb-2 mb-md-0">
                    <label for="page" class="fw-bold">Halaman</label>
                </div>
                <div class="col-auto">
                    <div>
                        <ul class="pagination pagination-sm m-0">
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> 1, 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    <<
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> ($input['page'] - 1), 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    <
                                </a>
                            </li>
                            <li class="page-item" style="max-width: 50px">
                                <form action="{{ route('user.tasks.index') }}" method="get">
                                    <input type="hidden" name="year" value="{{ $input['year'] }}">
                                    <input type="hidden" name="month" value="{{ $input['month'] }}">
                                    <input type="hidden" name="day" value="{{ $input['day'] }}">
                                    <input type="text" id="page" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                                </form>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> ($input['page'] + 1), 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    >
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> $totalPages, 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    >>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            {{-- List --}}
            <div class="row">
                @forelse ($items as $item)
                    <div class="col-md-6 my-3 col-lg-4 d-flex justify-content-between">
                        <div class="card w-100">
                            <div class="card-body row">
                                <h5 class="card-title col-12">
                                    <a href="{{ route('user.tasks.show', $item->id) }}" class="text-decoration-none text-black">
                                        {{ $item->title }}
                                    </a>
                                </h5>
                                <div class="col-12 position-relative overflow-hidden task-column" style="min-height: 1rem; max-height: 5rem;">
                                    <div class="card-text task-description">
                                        @php
                                            if (!empty($item->description)) {
                                                $description = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $item->description);
                                                $description = preg_replace('/(on\w+\s*=)/i', '', $description);
                                                echo $description;
                                            }
                                        @endphp
                                    </div>
                                    <div class="position-absolute bottom-0 w-100 continue-block" style="background: linear-gradient(to bottom, transparent, white); height: 2em">
                                    </div>
                                </div>
                                <div class="col-12 align-self-end">
                                    <a href="{{ route('user.tasks.show', $item->id) }}" class="card-link text-decoration-none">Detail</a>
                                    <a href="{{ route('user.tasks.edit', $item->id) }}" class="card-link text-decoration-none">Ubah</a>
                                    <form href="#" class="card-link text-decoration-none d-inline" method="POST" action="{{ route('user.tasks.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white text-danger border-0 p-0" onclick="return confirm('Tugas {{ $item->title }} ingin dihapus ?')">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 my-3">
                        <span class="text-muted">Belum ada tugas.</span>
                    </div>
                @endforelse
            </div>
            {{-- Pagination --}}
            <div class="row align-items-center">
                <div class="col-md-auto pe-0 mb-2 mb-md-0">
                    <label for="page2" class="fw-bold">Halaman</label>
                </div>
                <div class="col-auto">
                    <div>
                        <ul class="pagination pagination-sm m-0">
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> 1, 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    <<
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === 1 ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> ($input['page'] - 1), 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    <
                                </a>
                            </li>
                            <li class="page-item" style="max-width: 50px">
                                <form action="{{ route('user.tasks.index') }}" method="get">
                                    <input type="hidden" name="year" value="{{ $input['year'] }}">
                                    <input type="hidden" name="month" value="{{ $input['month'] }}">
                                    <input type="hidden" name="day" value="{{ $input['day'] }}">
                                    <input type="text" id="page2" class="form-control text-center form-control-sm" name="page" value="{{ $input['page'] }}">
                                </form>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> ($input['page'] + 1), 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    >
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link {{ $input['page'] === $totalPages || $input['page'] >= $totalPages ? 'disabled' : '' }}" href="{{ route('user.tasks.index', ['page'=> $totalPages, 'year' => $input['year'], 'month' => $input['month'], 'day' => $input['day']]) }}">
                                    >>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection