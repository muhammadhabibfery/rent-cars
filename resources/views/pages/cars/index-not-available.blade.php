@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Mobil')

@section('content')
    <h1>Data Mobil Tersewa</h1>


    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row justify-content-start">
                        <div class="col-sm-8">
                            <form action="{{ route('cars.not-available') }}">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control" placeholder="Cari mobil..."
                                        value="{{ request()->search }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit"
                                            id="button-addon2">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{ route('cars.not-available') }}" class="btn btn-dark">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Tahun</th>
                            <th scope="col">Plat Nomor</th>
                            <th scope="col">Warna</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cars as $car)
                            <tr>
                                <th scope="row">{{ $cars->currentPage() * 10 - 10 + $loop->iteration }}</th>
                                <td>{{ $car->name }}</td>
                                <td>{{ $car->years }}</td>
                                <td>{{ $car->plat_number }}</td>
                                <td>{{ $car->color }}</td>
                                <td class="price">{{ $car->price }}</td>
                                <td>
                                    <a href="{{ route('cars.show', ['status' => 'not-available', $car]) }}"
                                        class="btn btn-success btn-sm my-1">Detail</a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <p class="font-weight-bold text-center text-monospace">Data mobil tersewa tidak ditemukan
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Paginate Links --}}
                <div class="mt-4">
                    {{ $cars->withQueryString()->onEachSide(2)->links() }}
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
