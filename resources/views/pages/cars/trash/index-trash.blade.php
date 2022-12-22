@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Mobil Terhapus')

@section('content')
<div class="row mb-3">
    <div class="col-md-6 text-left">
        <h1>Daftar Mobil Terhapus</h1>
    </div>
    <div class="col-md-5 text-right">
        <a href="{{ route('cars.index') }}" class="btn btn-dark">Kembali</a>
    </div>
</div>


<div class="row justify-content-center">
    <div class="col-md-12">
        <x-car.car-search-bar :route="route('cars.trash')" type="trash" />

        <x-car.car-list :cars="$cars" type="trash" />
    </div>
</div>
@endsection

@push('styles')
@include('pages.cars.includes._select2-style')
@endpush

@push('scripts')
@include('pages.cars.includes._select2-script')
@include('pages.cars.includes._jquery-mask-script')
@endpush
