<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @include('pages.transactions.includes._bootstrap-4-styles')
    <title>Nota Transaksi</title>
</head>

<body class="py-3">
    <h1 class="text-center">NOTA TRANSAKSI</h1>
    <h5 class="text-center">No. Invoice : {{ $transaction->invoice_number }}</h5>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th scope="row" class="text-left">Nama Customer</th>
                        <td class="pl-5">{{ $transaction->customer->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Detail Mobil</th>
                        <td>
                            <ul>
                                @foreach ($transaction->cars as $car)
                                <li>
                                    {{ $car->name }} {{ $car->color }} {{ $car->years }} {{ $car->plat_number }}
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Tanggal Sewa</th>
                        <td class="pl-5">{{ $transaction->start_date_with_day }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Tanggal Selesai</th>
                        <td class="pl-5">{{ $transaction->finish_date_with_day }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Tanggal Pengembalian</th>
                        <td class="pl-5">{{ $transaction->return_date_with_day }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Status Pembayaran</th>
                        <td class="pl-5 font-weight-bold">
                            {{ $transaction->status !== 'COMPLETED' ? 'DP' : 'LUNAS' }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Total Harga</th>
                        <td class="pl-5">
                            {{ currencyFormat($transaction->total_price - $transaction->penalty_amount) }}
                        </td>
                    </tr>
                    @if ($transaction->total_late && $transaction->updated_by)
                    <tr>
                        <th scope="row" class="text-left">Total Keterlambatan</th>
                        <td class="pl-5">{{ $transaction->total_late }} hari</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Total Denda</th>
                        <td class="pl-5">{{ currencyFormat($transaction->penalty_amount) }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Total Harga + Total Denda</th>
                        <td class="pl-5">{{ currencyFormat($transaction->total_price) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th scope="row" class="text-left">Jumlah DP</th>
                        <td class="pl-5">
                            {{ currencyFormat(($transaction->total_price - $transaction->penalty_amount) / 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Sisa Pembayaran</th>
                        <td class="pl-5">
                            {{ currencyFormat($transaction->total_price - ($transaction->total_price -
                            $transaction->penalty_amount) / 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Jumlah Pelunasan</th>
                        <td class="pl-5">
                            {{ currencyFormat($transaction->payment_amount - ($transaction->total_price -
                            $transaction->penalty_amount) / 2) }}
                        </td>
                    </tr>
                    @if ($transaction->updated_by && $transaction->payment_amount - $transaction->total_price > 0)
                    <tr>
                        <th scope="row" class="text-left">Jumlah Kembalian</th>
                        <td class="pl-5">
                            {{ currencyFormat($transaction->payment_amount - $transaction->total_price) }}
                        </td>
                    </tr>
                    @endif
                </table>

                <p class="font-weight-bold" style="margin-top: 75px;">Penanggung Jawab</p>
                <br>
                <p class="font-weight-bold mt-5">{{ createdUpdatedDeletedBy($transaction->updated_by) }}</p>
            </div>

        </div>
    </div>

    @include('pages.transactions.includes._bootstrap-4-scripts')
</body>

</html>
