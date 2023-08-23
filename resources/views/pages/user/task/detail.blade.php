@extends('layouts.user')

@section('title', 'User - Tugas - ' . $item->title)

@section('description', empty($item->description) ? '' : strip_tags($item->description))

@section('content')
    <div class="col-11 shadow border p-3 bg-white align-self-start">
        <h2 class="text-center mb-1">Tugas</h2>
        <h4 class="text-center mb-3">{{ $item->title }}</h4>
        <div class="border-top pt-3">
            {{-- Task --}}
            <div class="row">
                <div class="col-12 text-break mb-3">
                    {!! $item->description !!}
                </div>
                <div class="col-12 table-responsive mb-3">
                    <table class="table w-100 table-bordered">
                        <tr>
                            <th class="w-25">Waktu Dimulai</th>
                            <td class="unix-value" data-unix="{{ $item->start_time }}"></td>
                        </tr>
                        <tr>
                            <th class="w-25">Waktu Selesai</th>
                            <td class="unix-value" data-unix="{{ $item->end_time }}"></td>
                        </tr>
                        <tr>
                            <th class="w-25">Waktu Dibuat</th>
                            <td class="unix-value" data-unix="{{ $item->created_at }}"></td>
                        </tr>
                        <tr>
                            <th class="w-25">Waktu Diubah</th>
                            <td class="unix-value" data-unix="{{ $item->updated_at }}"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('user.tasks.edit', $item->id) }}" class="btn btn-outline-primary me-1">
                    Ubah
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-light border border-black">
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('assets/js/UnixToLocal.js') }}" defer></script>
@endpush