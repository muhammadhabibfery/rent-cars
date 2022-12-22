@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Buat Transaksi')

@section('content')
<h1>Buat Transaksi</h1>

<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card mt-3">
            <div class="row">
                <div class="col-10">
                    <h5 class="card-header">Form Buat Transaksi</h5>
                </div>
                <div class="col-2">
                    <a href="{{ route('transactions.index') }}"
                        class="btn btn-outline-primary btn-sm float-right my-2 mr-3">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST" class="transaction-form" id="myfr">
                    @csrf
                    <div class="form-group">
                        <label for="invoice_number">No. Invoice</label>
                        <input class="form-control @error('invoice_number') is-invalid @enderror" type="text"
                            name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}" readonly>
                        @error('invoice_number')
                        <small class="fw-bold invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="customer d-block">Customer</label>
                        <br>
                        <select name="customer_id" class="form-control customer-select2" style="width: 100%">
                            <option></option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id')==$customer->id ? 'selected'
                                : '' }}>
                                {{ $customer->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <small class="font-weight-bold text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="start_date">Tanggal Sewa</label>
                        <div class="input-group flatpickr">
                            <input type="text" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                value="{{ old('start_date') }}"
                                placeholder="{{ old('start_date') ?: 'Pilih tanggal sewa' }}" data-input>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button">
                                    <a class="input-button" title="toggle" data-toggle>
                                        <i class="far fa-calendar-alt"></i>
                                    </a>
                                </button>
                            </div>
                            @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="duration">{{ __('Duration') }}</label>
                        <input type="number" name="duration"
                            class="form-control @error('duration') is-invalid @enderror" id="duration"
                            value="{{ old('duration') }}">
                        <small class="form-text text-muted">*Maksimal 30 hari</small>
                        @error('duration')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cars d-block">Mobil</label>
                        <br>
                        <select name="cars[]" multiple="multiple" class="form-control cars-select2-multiple"
                            style="width: 100%">
                            <option></option>
                            @foreach ($cars as $key => $car)
                            <option value="{{ $car->id }}-{{ $car->price }}" class="test daidnkandlkadnkawdadaddadadda"
                                {{ old('cars') ? (in_array("$car->id-$car->price", old("cars")) ? 'selected' : '') : ''
                                }}>
                                {{ $car->name }} {{ $car->color }} {{ $car->years }} {{ $car->plat_number }}
                            </option>
                            @endforeach
                        </select>
                        @error('cars')
                        <small class="font-weight-bold text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="total_price">Total Harga</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control @error('total_price') is-invalid @enderror price"
                                name="total_price" id="total_price" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="{{ old('total_price') }}" readonly>
                            @error('total_price')
                            <small class="font-weight-bold invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_amount">Jumlah Pembayaran</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Rp.</span>
                            </div>
                            <input type="text" class="form-control @error('payment_amount') is-invalid @enderror price"
                                name="payment_amount" id="payment_amount" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="{{ old('payment_amount') }}"
                                readonly>
                            @error('payment_amount')
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
    const inputInvoiceNumberElement = document.querySelector('#invoice_number');
    const selectCarsElement = document.querySelector('.cars-select2-multiple');
    let inputTotalPriceElement = document.querySelector('#total_price');
    const inputDurationElement = document.querySelector('#duration');
    const inputPaymentAmountElement = document.querySelector('#payment_amount');
    let platNumbers = 0;

    fetch("{{ route('transactions.generate-invoice') }}",{
        method: 'POST',
        headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        }
    })
    .then(response => response.json())
    .then(result => inputInvoiceNumberElement.value = result.data.invoice_number)
    .catch(error => alert('500 Internal Server Error'))

    $('#duration').on('change', function(ev){
        calculate(platNumbers, inputDurationElement.value, inputTotalPriceElement, inputPaymentAmountElement);
    });
    $('.cars-select2-multiple').on('select2:select', function (ev){
        platNumbers += parseInt(ev.params.data.id.split('-').pop());
        calculate(platNumbers, inputDurationElement.value, inputTotalPriceElement, inputPaymentAmountElement);
    });
    $('.cars-select2-multiple').on('select2:unselect', function (ev){
        platNumbers = platNumbers -= ev.params.data.id.split('-').pop();
        calculate(platNumbers, inputDurationElement.value, inputTotalPriceElement, inputPaymentAmountElement);
    });

    function calculate(platNumbers, duration, totalPrice, paymentAmout){
        totalPrice.value = duration.length > 0 ? platNumbers * parseInt(duration) : 0;
        paymentAmout.value = parseInt(totalPrice.value) / 2;
    }
</script>
@include('pages.transactions.includes._select2-transactions-script')
@include('pages.transactions.includes._jquery-mask-script')
@include('pages.transactions.includes._flatpickr-transactions-script')
@endpush
