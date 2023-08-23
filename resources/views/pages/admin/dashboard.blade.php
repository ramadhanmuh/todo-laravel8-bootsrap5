@extends('layouts.admin')

@section('title', 'Administrator - Dashboard')

@section('description', 'Halaman yang menampilkan rangkuman tentang aplikasi.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <div class="row">
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-primary rounded p-3 w-100">
                    <h5>Jumlah Pengguna</h5>
                    <b>{{ $totalUsers }}</b>
                </div>
            </div>
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-secondary rounded p-3 w-100">
                    <h5>Jumlah Administrator</h5>
                    <b>{{ $totalAdmins }}</b>
                </div>
            </div>
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-success rounded p-3 w-100">
                    <h5>Jumlah Tugas</h5>
                    <b>{{ $totalTasks }}</b>
                </div>
            </div>
        </div>
        <hr>
    </div>
@endsection