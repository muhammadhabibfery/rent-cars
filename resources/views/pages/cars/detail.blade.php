@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Detail Mobil')

@section('content')
<div class="row">
    <div class="col-md-6 text-left">
        <h1>Detail Mobil</h1>

    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('cars.index') }}" class="btn btn-dark">Kembali</a>
    </div>
</div>

<x-car.car-detail :car="$car" type="show" />
@endsection
