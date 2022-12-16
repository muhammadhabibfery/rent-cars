@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Detail Customer')

@section('content')
<div class="row">
    <div class="col-md-6 text-left">
        <h1>Detail Customer</h1>

    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('customers.index') }}" class="btn btn-dark">Kembali</a>
    </div>
</div>

<x-customer.customer-detail :customer="$customer" type="show" />
@endsection
