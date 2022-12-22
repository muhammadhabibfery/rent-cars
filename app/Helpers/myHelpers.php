<?php

use App\Car;
use App\Customer;
use App\Transaction;
use App\User;
use Carbon\Carbon;

/**
 * query a user who create or update or delete related data
 *
 * @param  int $id
 * @return string
 */
function createdUpdatedDeletedBy(int $id)
{
    return User::find($id)->name;
}

/**
 * transform Date Format
 *
 * @param  string $data
 * @param  string $format
 * @return string|object
 */
function transformDateFormat(string $data, ?string $format = null)
{
    $result = Carbon::parse($data);

    if ($format) $result = $result->translatedFormat($format);

    return $result;
}

/**
 * set integer Format
 *
 * @param  string $value
 * @return int
 */
function integerFormat(string $value)
{
    return (int) str_replace('.', '', $value);
}

/**
 * set and display currency format
 *
 * @param  int $value
 * @return string
 */
function currencyFormat(int $value)
{
    return "Rp. " .  number_format($value, 0, '.', '.');
}

/**
 * count of all customers
 *
 * @return int
 */
function countOfAllCustomers()
{
    return Customer::count();
}

/**
 * count of all cars
 *
 * @return int
 */
function countOfAllCars()
{
    return Car::count();
}

/**
 * count of all transaction status
 *
 * @param  string $status
 * @return int
 */
function countOfTransactionStatus(string $status)
{
    return Transaction::transactionStatus($status)->count();
}
