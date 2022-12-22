<div class="table-responsive {{ $type === 'trash' ? ' mt-4' : '' }}">
    <table class="table table-hover table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama</th>
                <th scope="col">Tahun</th>
                <th scope="col">Plat Nomor</th>
                <th scope="col">Warna</th>
                <th scope="col">Harga</th>
                <th scope="col">Status</th>
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
                <td
                    class="font-weight-bold font-italic{{ $car->status == 'AVAILABLE' ? ' text-primary' : ' text-success' }}">
                    {{ $car->status === 'AVAILABLE' ? 'Tersedia' : 'Disewa' }}
                </td>
                <td>
                    @if ($type === 'index')
                    <a href="{{ route('cars.show',  $car) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('cars.edit', $car) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                    @else
                    <a href="{{ route('cars.trash.show', $car->plat_number) }}"
                        class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('cars.restore', $car->plat_number) }}" class="btn btn-warning btn-sm my-1"
                        onclick="return confirm('Yakin ingin mengembalikan data mobil {{ $car->name }} ?')">Restore</a>
                    @endif
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deletecar{{ $car->plat_number }}modal">Hapus</a>
                </td>

                <!-- Delete Modal -->
                <div class="modal fade" id="deletecar{{ $car->plat_number }}modal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Hapus Mobil {{ $type === 'trash' ? 'permanen' : '' }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Apakah anda yakin ingin menghapus mobil <strong>{{ $car->name }}</strong> {{ $type
                                    === 'trash' ? ' secara permanen'
                                    : '' }} ?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form
                                    action="{{ $type === 'index' ? route('cars.destroy', $car) : route('cars.force-delete', $car->plat_number) }}"
                                    method="post" id="myfr">
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
                <td colspan="8">
                    <p class="font-weight-bold text-center text-monospace">
                        Data mobil {{ $type === 'trash' ? ' terhapus ' : '' }} kosong
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
