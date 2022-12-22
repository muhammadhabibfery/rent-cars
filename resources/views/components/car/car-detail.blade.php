<div class="row justify-content-center mb-5">
    <div class="col-md-10">
        <div class="card mt-3">
            <div class="row no-gutters">
                <div class="col-4 my-auto mx-auto text-center">
                    <img src="{{ $car->getImage() }}" class="rounded-circle img-thumbnail img-fluid" alt="car profile">
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
                                    Harga : <span class="font-weight-bold price">{{ currencyFormat($car->price)
                                        }}</span>
                                </p>
                                <p class="card-text">
                                    Status :
                                    <span
                                        class="font-weight-bold font-italic{{ $car->status == 'AVAILABLE' ? ' text-primary' : ' text-success' }}">{{
                                        $car->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-3 pl-4">
                    <p class="card-text">
                        <small class="text-muted">
                            Dibuat oleh : {{ createdUpdatedDeletedBy($car->created_by) }}
                        </small>
                    </p>
                </div>
                @if ($car->updated_by)
                <div class="col-md-4">
                    <p class="card-text">
                        <small class="text-muted">
                            Terakhir diubah oleh {{ createdUpdatedDeletedBy($car->updated_by) }},
                            {{ ' ' . $car->updated_at->diffForHumans() }}
                        </small>
                    </p>
                </div>
                @endif
                @if ($type === 'trash')
                <div class="col-md-4 text-right">
                    <p class="card-text">
                        <small class="text-muted">
                            Dihapus oleh : {{ createdUpdatedDeletedBy($car->deleted_by) }}
                        </small>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
