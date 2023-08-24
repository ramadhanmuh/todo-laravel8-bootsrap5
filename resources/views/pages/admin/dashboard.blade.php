@extends('layouts.admin')

@section('title', 'Administrator - Dashboard')

@section('description', 'Halaman yang menampilkan rangkuman tentang aplikasi.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <div class="row justify-content-center">
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-primary rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Pengguna</h5>
                    <b>{{ $totalUsers }}</b>
                </div>
            </div>
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-secondary rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Administrator</h5>
                    <b>{{ $totalAdmins }}</b>
                </div>
            </div>
            <div class="col-md-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-success rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas</h5>
                    <b>{{ $totalTasks }}</b>
                </div>
            </div>
            <div class="col-12 mt-2">
                <hr>
            </div>
            <div class="col-md-6 col-lg-5 col-xl-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-warning rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Hari Ini</h5>
                    <b id="dailyTotalTasks">Memuat...</b>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 col-xl-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-warning rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Bulan Ini</h5>
                    <b id="monthlyTotalTasks">Memuat...</b>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/admin/dashboard.js') }}" defer></script>
@endpush