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
            Route::group(['prefix' => 'cars'], function () {
                Route::get('/available', 'CarController@available')->name('cars.available');
                Route::get('/not-available', 'CarController@notAvailable')->name('cars.not-available');
                Route::get('/available/create', 'CarController@create')->name('cars.available.create');
                Route::post('/available', 'CarController@store')->name('cars.available.store');
                Route::get('/edit/{car:plat_number}', 'CarController@edit')->name('cars.available.edit');
                Route::patch('/available/{car:plat_number}', 'CarController@update')->name('cars.available.update');
                Route::delete('/available/{car:plat_number}', 'CarController@destroy')->name('cars.available.destroy');
                Route::get('/{status}/{car:plat_number}/', 'CarController@show')->name('cars.show');
            });
        });

        Route::macro('transactionRoutes', function () {
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
