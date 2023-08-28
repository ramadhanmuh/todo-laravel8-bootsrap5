@extends('layouts.owner')

@section('title', 'Owner - Dashboard')

@section('description', 'Halaman yang menampilkan rangkuman tentang aplikasi.')

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <div class="row justify-content-center">
            <div class="col-md-6 col-xl-3 d-flex my-2 mb-md-0">
                <div class="border border-2 border-primary rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Pengguna</h5>
                    <b id="totalUsers">Memuat...</b>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 d-flex my-2 mb-md-0">
                <div class="border border-2 border-secondary rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Administrator</h5>
                    <b id="totalAdministrators">Memuat...</b>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 d-flex my-2 mb-md-0">
                <div class="border border-2 border-success rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Owner</h5>
                    <b id="totalOwners">Memuat...</b>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 d-flex my-2 mb-md-0">
                <div class="border border-2 border-info rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas</h5>
                    <b id="totalTasks">Memuat...</b>
                </div>
            </div>
            <div class="col-12 mt-2">
                <hr>
            </div>
            <div class="col-lg-4 col-xl-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Hari Ini</h5>
                    <b id="totalTasksToday">Memuat...</b>
                </div>
            </div>
            <div class="col-lg-4 col-xl-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Bulan Ini</h5>
                    <b id="totalTasksThisMonth">Memuat...</b>
                </div>
            </div>
            <div class="col-lg-4 col-xl-4 d-flex my-2 mb-md-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Tahun Ini</h5>
                    <b id="totalTasksThisYear">Memuat...</b>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/owner/dashboard.js') }}" defer></script>
@endpush