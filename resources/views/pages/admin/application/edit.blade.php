@extends('layouts.admin')

@section('title', 'Administrator - Aplikasi - Ubah')

@section('description', 'Halaman yang menampilkan formulir pengubahan informasi aplikasi.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-3">Ubah Aplikasi</h2>
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
            <form action="{{ route('admin.application.update') }}" method="post" class="row">
                @csrf
                @method('PUT')
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ empty(old('name')) ? $application->name : old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tagline" class="form-label">Slogan</label>
                    <input type="text" class="form-control" id="tagline" name="tagline" value="{{ empty(old('tagline')) ? $application->tagline : old('tagline') }}" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="form-control" required>{{ empty(old('tagline')) ? $application->tagline : old('tagline') }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <label for="copyright" class="form-label">Copyright</label>
                    <input type="text" class="form-control" id="copyright" name="copyright" value="{{ empty(old('copyright')) ? $application->copyright : old('copyright') }}" required>
                </div>
                <div class="col-auto pe-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.application.index') }}" class="btn btn-light border border-dark">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection