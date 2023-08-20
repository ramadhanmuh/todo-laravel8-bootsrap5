@extends('layouts.user')

@section('title', 'User - Tugas')

@section('description', 'Halaman yang menampilkan daftar tugas pengguna.')

@section('content')
    <style>
        .ck-editor__editable_inline {
            min-height: 150px;
        }
    </style>
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Tambah Tugas</h2>
        <div class="border-top pt-3">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('user.tasks.store') }}" class="row" method="POST">
                <input type="hidden" name="id" required>
                @csrf
                <div class="col-12 mb-3">
                    <label for="title" class="form-label">Judul <small class="text-danger">*</small></label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_time" class="form-label">Waktu Mulai</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_time" class="form-label">Waktu Berakhir</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}">
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ url()->previous() }}" class="btn btn-light border border-black">Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ url('libraries/ckeditor5-39.0.1/build/ckeditor.js') }}"></script>
    <script defer src="{{ url('assets/js/user/task/form.js') }}"></script>
@endpush