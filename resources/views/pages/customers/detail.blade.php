@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Detail Customer')

@section('content')
    <h1>Detail Customer</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="row no-gutters">
                    <div class="col-4 my-auto mx-auto text-center">
                        <img src="{{ $customer->getAvatar() }}" class="rounded-circle img-thumbnail img-fluid"
                            alt="customer profile">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h3 class="card-title">{{ $customer->name }}</h3>
                            <p class="card-text">NIK : {{ $customer->nik }}</p>
                            <p class="card-text">Telepon : {{ $customer->phone }}</p>
                            <p class="card-text">Email : {{ $customer->email }}</p>
                            <span class="card-text d-block">Alamat :</span>
                            <small class="">{{ $customer->address }}</small>
                            <div class="row mt-3">
                                <div class="col-sm-8">
                                    <p class="card-text"><small class="text-muted">Terakhir diubah
                                            {{ $customer->updated_at->diffForHumans() }}</small></p>
                                </div>
                                <div class="col-sm-4">
                                    <a href="{{ route('customers.index') }}"
                                        class="btn btn-outline-primary btn-sm float-right">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
