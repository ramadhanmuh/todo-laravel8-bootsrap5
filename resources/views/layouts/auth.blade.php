<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <x-favicon/>
    <link rel="stylesheet" href="{{ url('libraries/bootstrap-5.3.0-dist/css/bootstrap.min.css') }}">
    <title>{{ $application->name }} - @yield('title')</title>
</head>
<body class="bg-white">
    <div class="fixed-top min-vh-100 row m-0 justify-content-center align-items-center bg-white d-none" id="loader">
        <div class="col-auto">
            <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="row min-vh-100 m-0 bg-white justify-content-center">
        <div class="col-auto py-3">
            <img src="{{ url('assets/images/logo.png') }}" alt="logo" height="100">
        </div>
        <div class="col-12">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <div class="row justify-content-center">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 p-5 py-3 align-self-end bg-white border">
            <span>
                {{ $application->copyright }}
            </span>
        </div>
    </div>
    <x-script/>
</body>
</html>