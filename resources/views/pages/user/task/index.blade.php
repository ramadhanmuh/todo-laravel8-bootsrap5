@extends('layouts.user')

@section('title', 'User - Tugas')

@section('description', 'Halaman yang menampilkan daftar tugas pengguna.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Tugas</h2>
        <div class="border-top pt-3">
            @if (session('addTaskSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil menambah tugas.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('changeTaskSuccessfully'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil mengubah tugas.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('user.tasks.create') }}" class="btn btn-primary">
                    Tambah
                </a>
            </div>
            <div class="row">
                <div class="col-md-6 my-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="#" class="text-decoration-none text-black">
                                    Card title
                                </a>
                            </h5>
                            <div class="position-relative">
                                <p class="card-text" style="overflow: hidden; max-height: 4.5rem;">Some quick example text to build on the card title and make up the bulk of the card's content. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Excepturi atque necessitatibus nihil deleniti eius ullam accusantium ex, dolor similique error assumenda possimus inventore corrupti sed aspernatur molestias vitae, sequi adipisci ad tenetur? Quibusdam corporis quae laudantium non reprehenderit voluptate, hic ullam deleniti unde quod deserunt facilis qui libero! Repellat, nesciunt?</p>
                                <div class="position-absolute bottom-0 w-100" style="background: linear-gradient(to bottom, transparent, white); height: 2em">
                                </div>
                            </div>
                            <a href="#" class="card-link text-decoration-none">Detail</a>
                            <a href="#" class="card-link text-decoration-none">Ubah</a>
                            <a href="#" class="card-link text-decoration-none">Hapus</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 my-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="card-link text-decoration-none">Detail</a>
                            <a href="#" class="card-link text-decoration-none">Ubah</a>
                            <a href="#" class="card-link text-decoration-none">Hapus</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 my-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="card-link text-decoration-none">Detail</a>
                            <a href="#" class="card-link text-decoration-none">Ubah</a>
                            <a href="#" class="card-link text-decoration-none">Hapus</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 my-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="card-link text-decoration-none">Detail</a>
                            <a href="#" class="card-link text-decoration-none">Ubah</a>
                            <a href="#" class="card-link text-decoration-none">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('assets/js/UnixToLocal.js') }}"></script>
@endpush