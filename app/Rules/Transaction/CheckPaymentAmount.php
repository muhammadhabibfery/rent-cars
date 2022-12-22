<?php

namespace App\Rules\Transaction;

use Illuminate\Contracts\Validation\Rule;

class CheckPaymentAmount implements Rule
{
    /**
     * The data under validation.
     *
     * @var array
     */
    protected $totalPrice;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request()->routeIs('transactions.update')) return $value >= $this->totalPrice;

        return empty($this->totalPrice) || $this->totalPrice < 1
            ? false
            : $this->totalPrice / 2 === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid :attribute.';
    }
}
