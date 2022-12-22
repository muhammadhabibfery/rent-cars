<?php

namespace App\Http\Requests;

use App\Rules\Transaction\CheckPaymentAmount;
use App\Rules\Transaction\CheckTotalPrice;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->request->all();

        if (request()->routeIs('transactions.store')) {
            if (empty($request['cars'])) $request['cars'] = [];

            return [
                'invoice_number' => ['required', 'string', 'size:31', Rule::unique('transactions', 'invoice_number')],
                'customer_id' => ['required', 'integer', 'exists:customers,id'],
                'start_date' => ['required', 'date_format:"Y-m-d H:i"', 'after:today'],
                'duration' => ['required', 'integer', 'min:1', 'max:30'],
                'cars' => ['required', 'array', 'max:2'],
                'cars.*' => ['required', 'string', 'exists:cars,id'],
                'total_price' => ['required', 'min:0', 'numeric', new CheckTotalPrice($request['cars'], $request['duration'])],
                'payment_amount' => ['required', 'min:0', 'numeric', new CheckPaymentAmount($request['total_price'])]
            ];
        }

        if (request()->routeIs('transactions.update')) {
            $request['total_price'] = $this->transaction->total_price - $this->transaction->payment_amount;
            return [
                'payment_amount' => ['required', 'min:0', 'numeric', new CheckPaymentAmount($request['total_price'])]
            ];
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->request->has('cars')) {
            $cars = [];

            foreach ($this->request->all()['cars'] as $car) {
                $cars[] = head(explode('-', $car));
            }

            $this->merge([
                'cars' => $cars,
            ]);
        }

        if ($this->request->has('total_price')) {
            $this->merge([
                'total_price' => integerFormat($this->request->all()['total_price'])
            ]);
        }

        if ($this->request->has('payment_amount')) {
            $this->merge([
                'payment_amount' => integerFormat($this->request->all()['payment_amount'])
            ]);
        }
    }
}
