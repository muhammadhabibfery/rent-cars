@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Edit Transaksi')

@section('content')
<h1>Edit Transaksi</h1>

<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="row">
                <div class="col-10">
                    <h5 class="card-header">Form Edit Transaksi</h5>
                </div>
                <div class="col-2">
                    <a href="{{ route('transactions.index') }}"
                        class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="transaction-form"
                    id="myfr">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="invoice_number">No. Invoice</label>
                        <input class="form-control" type="text" name="invoice_number" id="invoice_number"
                            value="{{ $transaction->invoice_number }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="customer d-block">Customer</label>
                        <br>
                        <select name="customer_id" class="form-control customer-select2" style="width: 100%" disabled>
                            <option></option>
                            <option value="{{ $transaction->customer_id }}" selected>
                                {{ $transaction->customer->name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cars d-block">Mobil</label>
                        <br>
                        <select name="cars[]" multiple="multiple" class="form-control cars-select2-multiple"
                            style="width: 100%" disabled>
                            <option></option>
                            @foreach ($transaction->cars as $car)
                            <option value="{{ $car->id }}-{{ $car->price }}" selected>
                                {{ $car->name }} {{ $car->color }} {{ $car->years }} {{ $car->plat_number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Tanggal Sewa</label>
                        <div class="input-group flatpickr">
                            <input type="text" name="start_date" class="form-control" id="start_date"
                                value="{{ $transaction->start_date }}" placeholder="{{ $transaction->start_date }}"
                                data-input disabled>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button">
                                    <a class="input-button" title="toggle" data-toggle>
                                        <i class="far fa-calendar-alt"></i>
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Tanggal Selesai</label>
                        <div class="input-group flatpickr">
                            <input type="text" name="finish_date" class="form-control" id="finish_date"
                                value="{{ $transaction->finish_date }}" placeholder="{{ $transaction->finish_date }}"
                                data-input disabled>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button">
                                    <a class="input-button" title="toggle" data-toggle>
                                        <i class="far fa-calendar-alt"></i>
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total_late">Total Keterlambatan</label>
                        <input class="form-control" type="text" name="total_late" id="total_late"
                            value="{{ $transaction->total_late ?: 0 }} hari" disabled>
                    </div>
                    <div class="form-group">
                        <label for="penalty_amount">Total Denda</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control price" name="penalty_amount" id="penalty_amount"
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"
                                value="{{ $transaction->penalty_amount ?: 0 }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total_price">
                            Total Harga {{ $transaction->total_late ? ' ( + Total Denda )' : ''}}
                        </label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control price" name="total_price" id="total_price"
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"
                                value="{{ $transaction->total_price }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dp_amount">Jumlah DP</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control price" name="dp_amount" id="dp_amount"
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"
                                value="{{ $transaction->payment_amount }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remaining_payment">Sisa Pembayaran</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control price" name="remaining_payment"
                                id="remaining_payment" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                                value="{{ (int) $transaction->total_price - $transaction->payment_amount }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_amount">Jumlah Pelunasan</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control @error('payment_amount') is-invalid @enderror price"
                                name="payment_amount" id="payment_amount" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="{{ old('payment_amount') }}">
                            @error('payment_amount')
                            <small class="font-weight-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="return_amount">Jumlah Kembalian</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control @error('return_amount') is-invalid @enderror price"
                                name="return_amount" id="return_amount" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" disabled>
                            @error('return_amount')
                            <small class="font-weight-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-4 btn-submit" id="btnfr">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@include('pages.transactions.includes._select2-style')
@include('pages.transactions.includes._flatpickr-style')
@endpush

@push('scripts')
<script>
    const inputPaymentAmount = document.querySelector('#payment_amount');
    const inputRemainingPayment = document.querySelector('#remaining_payment');
    const inputReturnAmount = document.querySelector('#return_amount');

    inputPaymentAmount.addEventListener('change', function(ev){
        fetch("{{ route('transactions.generate-return-amount') }}",{
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body:JSON.stringify({
                    "payment_amount": ev.target.value,
                    "total_price": inputRemainingPayment.value
                })
        })
        .then(response => response.json())
        .then(result => inputReturnAmount.value = result.data.return_amount)
        .catch(error => alert('Invalid payment amount'))
    })
</script>
@include('pages.transactions.includes._select2-transactions-script')
@include('pages.transactions.includes._jquery-mask-script')
@include('pages.transactions.includes._flatpickr-transactions-script')
@endpush
