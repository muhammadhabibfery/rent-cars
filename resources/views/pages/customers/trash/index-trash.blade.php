@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Customer Terhapus')

@section('content')
<div class="row">
    <div class="col-md-6 text-left">
        <h1>Data Customer Terhapus</h1>
    </div>
    <div class="col-md-5 text-right">
        <a href="{{ route('customers.index') }}" class="btn btn-dark">Kembali</a>
    </div>
</div>


<div class="row justify-content-center mt-4">
    <div class="col-md-10">

        <x-customer.customer-search-bar :route="route('customers.trash')" />

        <x-customer.customer-list :customers="$customers" type="trash" />
    </div>
</div>
@endsection
