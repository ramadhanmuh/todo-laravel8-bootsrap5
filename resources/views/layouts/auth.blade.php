<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="@yield('description')">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('assets/favicon/favicon-16x16.png') }}">
    <link rel="stylesheet" href="{{ url('libraries/bootstrap-5.3.0-dist/css/bootstrap.min.css') }}">
    <title>ToDo - @yield('title')</title>
</head>
<body class="bg-white">
    <div class="row m-0 min-vh-100 align-items-center justify-content-center">
        @yield('content')
    </div>
    <script defer src="{{ url('libraries/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ url('libraries/jQuery-3.7.0/jquery.min.js') }}"></script>
</body>
</html>