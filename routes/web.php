<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});



Route::group(['middleware' => ['prevent-back']], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');

        Route::adminRoutes();
        Route::customerRoutes();
        Route::carRoutes();

        Route::profileRoutes();
    });
});

// Next : Buat Fitur Transaksi

//NOTE : Buat opsional parameter pada show/detail car, Supaya tidak perlu mengisi parameter pada method show dalam CarController
