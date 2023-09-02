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
            <div class="col-xl-4 d-flex my-2 my-xl-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Hari Ini</h5>
                    <b id="totalTasksToday">Memuat...</b>
                </div>
            </div>
            <div class="col-xl-4 d-flex my-2 my-xl-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Bulan Ini</h5>
                    <b id="totalTasksThisMonth">Memuat...</b>
                </div>
            </div>
            <div class="col-xl-4 d-flex my-2 my-xl-0">
                <div class="border border-2 border-dark rounded p-3 w-100">
                    <h5 class="border-bottom pb-2">Jumlah Tugas Dibuat Tahun Ini</h5>
                    <b id="totalTasksThisYear">Memuat...</b>
                </div>
            </div>
            <div class="col-12 mt-2">
                <hr>
            </div>
            <div class="col-12 my-2">
                <h5 class="text-center mb-3">Total Tugas Dibuat Hari Ini</h5>
                <form class="row align-items-center mb-2" action="" id="totalTasksPerHourForm">
                    <div class="col-7 col-md-auto">
                        <input type="date" class="form-control date-input" id="totalTasksPerHourDate">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary" type="submit">Terapkan</button>
                    </div>
                </form>
                <canvas id="totalTasksPerHour">Your browser does not support the canvas element.</canvas>
            </div>
            <div class="col-12 mb-2 mt-4">
                <h5 class="text-center mb-3">Total Tugas Dibuat Minggu Ini</h5>
                <form class="row align-items-center mb-2" action="" id="totalTasksDailyForm">
                    <div class="col-7 col-md-auto">
                        <input type="date" class="form-control date-input" id="totalTasksDailyDate">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary" type="submit">Terapkan</button>
                    </div>
                </form>
                <canvas id="totalTasksDaily">Your browser does not support the canvas element.</canvas>
            </div>

            <div class="col-12 mb-2 mt-4">
                <h5 class="text-center mb-3">Total Tugas Dibuat Tahun Ini</h5>
                <form class="row align-items-center mb-2" action="" id="totalTasksMonthlyForm">
                    <div class="col-7 col-md-auto">
                        <input type="date" class="form-control date-input" id="totalTasksMonthlyDate">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary" type="submit">Terapkan</button>
                    </div>
                </form>
                <canvas id="totalTasksMonthly">Your browser does not support the canvas element.</canvas>
            </div>

            <div class="col-12 mt-4">
                <h5 class="text-center mb-3">Pertumbuhan Total Pendaftar</h5>
                <form class="row align-items-center mb-2" action="" id="userGrowthForm">
                    <div class="col-7 col-md-auto">
                        <input type="date" class="form-control date-input" id="userGrowthDate">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary" type="submit">Terapkan</button>
                    </div>
                </form>
                <canvas id="userGrowth">Your browser does not support the canvas element.</canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('libraries/chart.js-4.4.0/dist/chart.umd.js') }}" defer></script>
    <script src="{{ url('assets/js/owner/dashboard.js') }}" defer></script>
@endpush