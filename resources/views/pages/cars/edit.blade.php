@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Ubah Mobil')

@section('content')
    <h1>Ubah Mobil</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="row">
                    <div class="col-10">
                        <h5 class="card-header">Form Ubah Mobil</h5>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('cars.available') }}"
                            class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('cars.available.update', $car) }}" method="POST" enctype="multipart/form-data" id="myfr">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                                id="name" value="{{ old('name', $car->name) }}">
                            @error('name')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="merk">Merk</label>
                            <input class="form-control @error('merk') is-invalid @enderror" type="text" name="merk"
                                id="merk" value="{{ old('merk', $car->merk) }}">
                            @error('merk')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="years">Tahun</label>
                            <input class="form-control @error('years') is-invalid @enderror" type="text" name="years"
                                id="years" value="{{ old('years', $car->years) }}">
                            @error('years')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="plat_number">Plat Nomor</label>
                            <input class="form-control @error('plat_number') is-invalid @enderror" type="text"
                                name="plat_number" id="plat_number" value="{{ old('plat_number', $car->plat_number) }}">
                            @error('plat_number')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="color">Warna</label>
                            <input class="form-control @error('color') is-invalid @enderror" type="text" name="color"
                                id="color" value="{{ old('color', $car->color) }}">
                            @error('color')
                            <small class="fw-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                                </div>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                    id="price" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default" value="{{ old('price', $car->price) }}">
                                @error('price')
                                <small class="fw-bold invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5 my-auto mx-auto text-center">
                                    <label class="float-left">Foto Mobil saat ini</label>
                                    <img src="{{ $car->getCarImage() }}" class="rounded-circle img-thumbnail img-fluid"
                                        alt="car image" width="225" height="225">
                                </div>
                                <div class="col-sm-7 my-auto mx-auto">
                                    <label for="gambar">Ubah Foto Mobil</label>
                                    <small class="ml-2">(*opsional)</small>
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#price').mask('000.000.000', {
                reverse: true
            });
        });

    </script>
@endpush
