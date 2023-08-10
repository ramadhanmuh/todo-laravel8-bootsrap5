@extends('layouts.auth')

@section('title', $application->tagline)

@section('description', $application->description)

@section('content')
    <div class="col-11 col-md-5 col-lg-4 col-xl-3 shadow border p-3 bg-white">
        <h1 class="text-center mb-3">{{ $application->name }}</h1>
        <p>{{ $application->description }}</p>
        <div class="row justify-content-between w-100">
            <div class="col-auto">
                <a href="{{ route('login.show') }}" class="btn btn-primary">
                    Login
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('register.show') }}" class="btn btn-secondary">
                    Daftar
                </a>
            </div>
        </div>
    </div>
@endsection