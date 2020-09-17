@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Ubah Customer')

@section('content')
    <h1>Ubah Customer</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="row">
                    <div class="col-10">
                        <h5 class="card-header">Form Ubah Customer</h5>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('customers.index') }}"
                            class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data"
                        id="myfr">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                                id="name" value="{{ old('name', $customer->name) }}">
                            @error('name')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input class="form-control @error('nik') is-invalid @enderror" type="text" name="nik" id="nik"
                                value="{{ old('nik', $customer->nik) }}">
                            @error('nik')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                                id="phone" value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                                id="email" value="{{ old('email', $customer->email) }}">
                            @error('email')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                id="address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5 my-auto mx-auto text-center">
                                    <label class="float-left">Profil Customer saat ini</label>
                                    <img src="{{ $customer->getAvatar() }}" class="rounded-circle img-thumbnail img-fluid"
                                        alt="customer profile">
                                </div>
                                <div class="col-sm-7 my-auto mx-auto">
                                    <label for="gambar">Ubah Profil Customer</label>
                                    <input id="gambar" name="gambar" type="file"
                                        class="form-control @error('gambar') is-invalid @enderror"
                                        value="{{ old('gambar') }}">
                                    @error('gambar')
                                    <small class="fw-bold text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4" id="btnfr">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
