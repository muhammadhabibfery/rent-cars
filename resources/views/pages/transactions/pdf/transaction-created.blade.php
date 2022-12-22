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
                        <th scope="row" class="text-left">Status Pembayaran</th>
                        <td class="pl-5 font-weight-bold">
                            {{ $transaction->status !== 'COMPLETED' ? 'DP' : 'LUNAS' }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Total Harga</th>
                        <td class="pl-5">{{ currencyFormat($transaction->total_price) }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-left">Jumlah DP</th>
                        <td class="pl-5">{{ currencyFormat($transaction->payment_amount) }}</td>
                    </tr>
                </table>

                <p class="font-weight-bold" style="margin-top: 75px;">Penanggung Jawab</p>
                <br>
                <p class="font-weight-bold mt-5">{{ createdUpdatedDeletedBy($transaction->created_by) }}</p>
            </div>
        </div>
    </div>

    @include('pages.transactions.includes._bootstrap-4-scripts')
</body>

</html>
