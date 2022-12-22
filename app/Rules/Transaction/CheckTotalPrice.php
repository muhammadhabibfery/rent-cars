<?php

namespace App\Rules\Transaction;

use App\Car;
use Illuminate\Contracts\Validation\Rule;

class CheckTotalPrice implements Rule
{
    /**
     * The data under validation.
     *
     * @var array
     */
    protected $cars, $duration;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($cars, $duration)
    {
        $this->cars = $cars;
        $this->duration = $duration;
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
        if (count($this->cars) < 1 || empty($this->duration)) return false;

        $total = 0;
        foreach ($this->cars as $carId) $total += (Car::findOrFail($carId)->price * (int) $this->duration);
        return  $total === $value;
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
