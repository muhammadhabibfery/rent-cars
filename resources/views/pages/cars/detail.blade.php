@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Detail Mobil')

@section('content')
    <h1>Detail Mobil</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="row no-gutters">
                    <div class="col-4 my-auto mx-auto text-center">
                        <img src="{{ $car->getCarImage() }}" class="rounded-circle img-thumbnail img-fluid"
                            alt="car profile">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-text">Nama : {{ $car->name }}</p>
                                    <p class="card-text">Merk : {{ $car->merk }}</p>
                                    <p class="card-text">Tahun : {{ $car->years }}</p>
                                    <p class="card-text">
                                        Plat Nomor : <span class="font-weight-bold">{{ $car->plat_number }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text">Warna : {{ $car->years }}</p>
                                    <p class="card-text">
                                        Harga : <span class="font-weight-bold price">{{ $car->price }}</span>
                                    </p>
                                    <p class="card-text">
                                        Status :
                                        <span
                                            class="font-weight-bold font-italic{{ $car->status == 'AVAILABLE' ? ' text-success' : ' text-danger' }}">{{ $car->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-8">
                                    <p class="card-text"><small class="text-muted">Terakhir diubah
                                            {{ $car->updated_at->diffForHumans() }}</small></p>
                                </div>
                                <div class="col-sm-4">
                                    <a href="{{ $car->status === 'AVAILABLE' ? route('cars.available') : route('cars.not-available') }}"
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('.price').mask('000.000.000', {
                reverse: true
            });
        });

    </script>
@endpush
