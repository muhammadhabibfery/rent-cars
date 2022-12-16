@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Buat Transaksi')

@section('content')
    <h1>Buat Transaksi</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="row">
                    <div class="col-10">
                        <h5 class="card-header">Form Buat Transaksi</h5>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('transactions.index') }}"
                            class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" id="myfr">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                                id="name" value="{{ old('name') }}">
                            @error('name')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input class="form-control @error('nik') is-invalid @enderror" type="text" name="nik" id="nik"
                                value="{{ old('nik') }}">
                            @error('nik')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                                id="phone" value="{{ old('phone') }}">
                            @error('phone')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <small class="ml-2">(*opsional)</small>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                                id="email" value="{{ old('email') }}">
                            @error('email')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                id="address">{{ old('address') }}</textarea>
                            @error('address')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4" id="btnfr">Buat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
