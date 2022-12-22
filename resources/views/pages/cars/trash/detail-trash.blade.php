@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Detail Mobil')

@section('content')
<div class="row">
    <div class="col-md-6 text-left">
        <h1>Detail Mobil Terhapus</h1>

    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('cars.trash') }}" class="btn btn-dark">Kembali</a>
    </div>
</div>

<x-car.car-detail :car="$car" type="trash" />
@endsection

@push('scripts')
@include('pages.cars.includes._jquery-mask-script')
@endpush
