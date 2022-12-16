@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Ubah Customer')

@section('content')
<h1>Ubah Customer</h1>

<x-customer.customer-form :route="route('customers.update', $customer)" :customer="$customer" type="update" />
@endsection
