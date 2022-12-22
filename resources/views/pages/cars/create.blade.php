@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Tambah Mobil')

@section('content')
<h1>Tambah Mobil</h1>

<x-car.car-form :route="route('cars.store')" :car="null" type="create" />
@endsection

@push('scripts')
@include('pages.cars.includes._jquery-mask-script')
@endpush
