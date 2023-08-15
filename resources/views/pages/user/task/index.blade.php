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
            <form class="row" action="{{ route('user.tasks.index') }}">
                <div class="col-12 col-lg-7 col-xl-5">
                    <div class="row">
                        <div class="col col-md">
                            <div class="row align-items-center">
                                <div class="col-auto pe-0">
                                    <label for="year" class="col-form-label fw-bold">Tahun</label>
                                </div>
                                <div class="col-12 col-md">
                                    <input type="text" class="form-control form-control-sm" id="year" name="year" placeholder="****">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-auto pe-0">
                                    <label for="month" class="col-form-label fw-bold">Bulan</label>
                                </div>
                                <div class="col-12 col-md">
                                    <input type="text" class="form-control form-control-sm" id="month" name="month" placeholder="**">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-auto pe-0">
                                    <label for="day" class="col-form-label fw-bold">Tanggal</label>
                                </div>
                                <div class="col-12 col-md">
                                    <input type="text" class="form-control form-control-sm" id="day" name="day" placeholder="**">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto align-self-end align-self-md-center">
                            <button class="btn btn-primary btn-sm" type="submit">Saring</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-6 my-3 col-lg-4 d-flex justify-content-between">
                    <div class="card">
                        <div class="card-body row">
                            <h5 class="card-title col-12">
                                <a href="#" class="text-decoration-none text-black">
                                    Card title
                                </a>
                            </h5>
                            <div class="col-12 position-relative overflow-hidden task-column" style="max-height: 5rem;">
                                <p class="card-text task-description">Some quick example text to build on the card title and make up the bulk of the card's content. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Excepturi atque necessitatibus nihil deleniti eius ullam accusantium ex, dolor similique error assumenda possimus inventore corrupti sed aspernatur molestias vitae, sequi adipisci ad tenetur? Quibusdam corporis quae laudantium non reprehenderit voluptate, hic ullam deleniti unde quod deserunt facilis qui libero! Repellat, nesciunt?</p>
                                <div class="position-absolute bottom-0 w-100 continue-block" style="background: linear-gradient(to bottom, transparent, white); height: 2em">
                                </div>
                            </div>
                            <div class="col-12 align-self-end">
                                <a href="{{ route('user.tasks.show', 1) }}" class="card-link text-decoration-none">Detail</a>
                                <a href="{{ route('user.tasks.edit', 1) }}" class="card-link text-decoration-none">Ubah</a>
                                <form href="#" class="card-link text-decoration-none d-inline" method="POST" action="{{ route('user.tasks.destroy', 1) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white text-danger border-0 p-0">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script defer src="{{ url('assets/js/UnixToLocal.js') }}"></script> --}}
@endpush