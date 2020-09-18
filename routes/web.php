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

        Route::profileRoutes();
    });
});

// Current Problem : Tidak berhasil Login meskipun email & password sudah Benar.
                        // (Analisa saat ini : Terjadi masalah pada UserFactory dan UserSeeder)
                            // UserFactory : Field password tidak diisi tapi bisa terisi otomatis dan sudah dienkripsi.
