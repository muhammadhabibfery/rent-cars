@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Customer')

@section('content')
<h1>Data Customer</h1>


<div class="row justify-content-center mt-3">
    <div class="col-md-10">

        <x-customer.customer-search-bar :route="route('customers.index')" />

        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-block mb-4 mt-2">Tambah Customer</a>

        @if (auth()->user()->name === 'Administrator')
        <a href="{{ route('customers.trash') }}" class="btn btn-warning btn-block my-4">Data Customer Terhapus</a>
        @endif

        <x-customer.customer-list :customers="$customers" type="index" />
    </div>
</div>
@endsection
