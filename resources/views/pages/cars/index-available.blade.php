@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Mobil')

@section('content')
    <h1>Data Mobil Tersedia</h1>


    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row justify-content-start">
                        <div class="col-sm-8">
                            <form action="{{ route('cars.available') }}">
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
                            <a href="{{ route('cars.available') }}" class="btn btn-dark">Reset</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('cars.available.create') }}" class="btn btn-primary btn-block my-4">Tambah Mobil</a>
            <div class="table-responsive">

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
                                    <a href="{{ route('cars.show', ['status' => 'available', $car]) }}"
                                        class="btn btn-success btn-sm my-1">Detail</a>
                                    <a href="{{ route('cars.available.edit', $car) }}"
                                        class="btn btn-warning btn-sm my-1">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deletecar{{ $car->plat_number }}modal">Hapus</a>
                                </td>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deletecar{{ $car->plat_number }}modal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Hapus Mobil</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    Apakah anda yakin ingin menghapus mobil
                                                    <strong>{{ $car->name }}</strong> ?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <form action="{{ route('cars.available.destroy', $car) }}" method="post"
                                                    id="myfr">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" id="btnfr">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <p class="font-weight-bold text-center text-monospace">Data mobil tersedia tidak ditemukan
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
