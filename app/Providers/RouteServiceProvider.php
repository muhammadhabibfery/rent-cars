<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('adminRoutes', function () {
            Route::resource('admins', 'UserController')
                ->parameters(['admins' => 'user:phone'])
                ->except(['show', 'edit', 'update']);
        });

        Route::macro('customerRoutes', function () {
            Route::get('/customers/trash', 'CustomerController@indexTrash')->name('customers.trash');
            Route::get('/customers/trash/{phone}', 'CustomerController@showTrash')->name('customers.trash.detail');
            Route::get('/customers/restore/{phone}', 'CustomerController@restore')->name('customers.restore');
            Route::delete('/customers/force-delete/{phone}', 'CustomerController@forceDelete')->name('customers.force-delete');
            Route::get('/customers/edit/{customer:phone}', 'CustomerController@edit')->name('customers.edit');
            Route::resource('customers', 'CustomerController')
                ->parameters(['customers' => 'customer:phone'])
                ->except(['edit']);
        });

        Route::macro('carRoutes', function () {
            Route::get('/cars/trash', 'CarController@indexTrash')->name('cars.trash');
            Route::get('/cars/trash/{plat_number}', 'CarController@showTrash')->name('cars.trash.show');
            Route::get('/cars/restore/{plat_number}', 'CarController@restore')->name('cars.restore');
            Route::delete('/cars/force-delete/{plat_number}', 'CarController@forceDelete')->name('cars.force-delete');
            Route::get('/cars/edit/{car:plat_number}', 'CarController@edit')->name('cars.edit');
            Route::resource('cars', 'CarController')
                ->parameters(['cars' => 'car:plat_number'])
                ->except(['edit']);
        });

        Route::macro('transactionRoutes', function () {
            Route::post('/transactions/generate-invoice', 'TransactionController@generateInvoiceNumber')
                ->name('transactions.generate-invoice');
            Route::post('/transactions/generate-return-amount', 'TransactionController@generateReturnAmount')
                ->name('transactions.generate-return-amount');
            Route::get('/transactions/print-pdf/{transaction:invoice_number}/{update?}', 'TransactionController@printTransaction')
                ->name('transactions.print-pdf');
            Route::get('/transactions/edit/{transaction:invoice_number}', 'TransactionController@edit')->name('transactions.edit');
            Route::resource('transactions', 'TransactionController')
                ->parameters(['transactions' => 'transaction:invoice_number'])
                ->except(['edit']);
        });

        Route::macro('profileRoutes', function () {
            Route::get('/profile', 'ProfileController@editProfile')->name('profiles.edit');
            Route::patch('/profile', 'ProfileController@updateProfile')->name('profiles.update');
            Route::get('/profile/password', 'ProfileController@editPassword')->name('profiles.password.edit');
            Route::patch('/profile/password', 'ProfileController@updatePassword')->name('profiles.password.update');
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
