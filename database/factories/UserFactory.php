<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'administrator',
        'email' => 'admin@kbf-trans.com',
        'nik' => '121212121212',
        'password' => Hash::make('aaaaa'),
        'phone' => '08123456789',
        'address' => 'jl. pamulang tangerang selatan',
    ];
});
