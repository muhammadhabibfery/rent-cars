<div class="row justify-content-center mb-5">
    <div class="col-md-10">
        <div class="card mt-3">
            <div class="row no-gutters">
                <div class="card-body">
                    <p class="card-text">No. Invoice : {{ $transaction->invoice_number }}</p>
                    <p class="card-text">Customer : {{ $transaction->customer->name }}</p>
                    <span class="card-text">Jenis Mobil :</span>
                    <ul>
                        @foreach ($transaction->cars as $car)
                        <li>{{ $car->name }} {{ $car->color }} {{ $car->years }} {{ $car->plat_number }}</li>
                        @endforeach
                    </ul>
                    <p class="card-text">
                        Tanggal Sewa : <span class="font-weight-bold">{{ $transaction->start_date_with_day }}</span>
                    </p>
                    <p class="card-text">
                        Tanggal Selesai : <span class="font-weight-bold">{{ $transaction->finish_date_with_day }}</span>
                    </p>
                    @if ($transaction->updated_by)
                    <p class="card-text">
                        Tanggal Pengembalian : <span class="font-weight-bold">
                            {{ $transaction->return_date_with_day }}</span>
                    </p>
                    @endif
                    <p class="card-text">
                        Status Pembayaran : <span class="font-weight-bold price">{{ $transaction->status }}</span>
                    </p>
                    @if ($transaction->total_late && $transaction->updated_by)
                    <p class="card-text">
                        Total Keterlambatan : <span class="font-weight-bold price">
                            {{ $transaction->total_late }} hari
                        </span>
                    </p>
                    <p class="card-text">
                        Total Denda : <span class="font-weight-bold price">
                            {{ currencyFormat($transaction->penalty_amount) }}</span>
                    </p>
                    @endif
                    <p class="card-text">
                        Total Harga {{ $transaction->total_late && $transaction->updated_by ? '( + Total Denda)' : '' }}
                        :
                        <span class="font-weight-bold price">{{ currencyFormat($transaction->total_price)}}</span>
                    </p>
                    <p class="card-text">
                        Jumlah Pembayaran : <span class="font-weight-bold price">
                            {{ currencyFormat($transaction->payment_amount) }}</span>
                    </p>
                    <p class="card-text">
                        Status Transaksi:
                        <span class="font-weight-bold font-italic {{ $transaction->transaction_status[0] }}">
                            {{ $transaction->transaction_status[1] }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-3 pl-4">
                    <p class="card-text">
                        <small class="text-muted">
                            Dibuat oleh : {{ createdUpdatedDeletedBy($transaction->created_by) }}
                        </small>
                    </p>
                </div>
                @if ($transaction->updated_by)
                <div class="col-md-4">
                    <p class="card-text">
                        <small class="text-muted">
                            Terakhir diubah oleh {{ createdUpdatedDeletedBy($transaction->updated_by) }},
                            {{ ' ' . $transaction->updated_at->diffForHumans() }}
                        </small>
                    </p>
                </div>
                @endif
                @if ($type === 'trash')
                <div class="col-md-4 text-right">
                    <p class="card-text">
                        <small class="text-muted">
                            Dihapus oleh : {{ createdUpdatedDeletedBy($transaction->deleted_by) }}
                        </small>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
