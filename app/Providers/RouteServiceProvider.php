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
            Route::get('/customers/edit/{customer:slug}', 'CustomerController@edit')->name('customers.edit');
            Route::resource('customers', 'CustomerController')
                ->parameters(['customers' => 'customer:slug'])
                ->except(['edit']);
        });

        Route::macro('carRoutes', function () {
            Route::get('/cars/available', 'CarController@available')->name('cars.available');
            Route::get('/cars/not-available', 'CarController@notAvailable')->name('cars.not-available');
            Route::get('/cars/available/create', 'CarController@create')->name('cars.available.create');
            Route::post('/cars/available', 'CarController@store')->name('cars.available.store');
            Route::get('/cars/edit/{car:plat_number}', 'CarController@edit')->name('cars.available.edit');
            Route::patch('/cars/available/{car:plat_number}', 'CarController@update')->name('cars.available.update');
            Route::delete('/cars/available/{car:plat_number}', 'CarController@destroy')->name('cars.available.destroy');
            Route::get('/cars/{status}/{car:plat_number}/', 'CarController@show')->name('cars.show');
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
