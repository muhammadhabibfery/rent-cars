@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Mobil')

@section('content')
<h1 class="mb-3">Daftar Mobil</h1>

<div class="row justify-content-center">
    <div class="col-md-12">
        <x-car.car-search-bar :route="route('cars.index')" type="index" />

        <a href="{{ route('cars.create') }}" class="btn btn-primary btn-block my-4">Tambah Mobil</a>

        @if (auth()->user()->name === 'Administrator')
        <a href="{{ route('cars.trash') }}" class="btn btn-warning btn-block my-4">Data Mobil Terhapus</a>
        @endif

        <x-car.car-list :cars="$cars" type="index" />
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
