@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Ubah Password')

@section('content')
<h1>Ubah Password</h1>

<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="row">
                <div class="col-10">
                    <h5 class="card-header">Form Ubah Password</h5>
                </div>
                <div class="col-2">
                    <a href="{{ route('dashboard') }}"
                        class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('profiles.password.update') }}" method="POST" id="myfr">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label for="current_password">Password saat ini</label>
                        <input class="form-control @error('current_password') is-invalid @enderror" type="password"
                            name="current_password" id="current_password">
                        @error('current_password')
                        <small class="fw-bold invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password baru</label>
                        <input class="form-control @error('new_password') is-invalid @enderror" type="password"
                            name="new_password" id="new_password">
                        @error('new_password')
                        <small class="fw-bold invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password</label>
                        <input class="form-control @error('new_password_confirmation') is-invalid @enderror"
                            type="password" name="new_password_confirmation" id="new_password_confirmation">
                        @error('new_password_confirmation')
                        <small class="fw-bold invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-4" id="btnfr">Edit Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
