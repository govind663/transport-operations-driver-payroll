<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\backend\Auth\LoginController;
use App\Http\Controllers\backend\Auth\RegisterController;
use App\Http\Controllers\backend\Auth\ForgotPasswordController;
use App\Http\Controllers\backend\Auth\ResetPasswordController;

use App\Http\Controllers\backend\HomeController as BackendHomeController;

/*
|--------------------------------------------------------------------------
| Middleware
|--------------------------------------------------------------------------
*/
use App\Http\Middleware\OptimizeImagesMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('admin.login');

})->name('frontend.home');


/*
|--------------------------------------------------------------------------
| Login Redirect
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {

    return Auth::guard('web')->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('admin.login');

})->name('login');


/*
|--------------------------------------------------------------------------
| Admin Guest Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware([
        RedirectIfAuthenticatedCustom::class,
        OptimizeImagesMiddleware::class,
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */
        Route::get('/', [LoginController::class, 'login'])
            ->name('admin.login');

        Route::post('/login', [LoginController::class, 'authenticate'])
            ->name('admin.login.store');

        /*
        |--------------------------------------------------------------------------
        | Register
        |--------------------------------------------------------------------------
        */
        Route::get('/register', [RegisterController::class, 'register'])
            ->name('admin.register');

        Route::post('/register', [RegisterController::class, 'store'])
            ->name('admin.register.store');

        /*
        |--------------------------------------------------------------------------
        | Forgot Password
        |--------------------------------------------------------------------------
        */
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('admin.password.request');

        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('admin.password.email');

        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
            ->name('admin.password.reset');

        Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])
            ->name('admin.password.update');
    });


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware([
        'auth:web',
        PreventBackHistoryMiddleware::class,
        OptimizeImagesMiddleware::class,
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [BackendHomeController::class, 'adminHome'])
            ->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */
        Route::controller(BackendHomeController::class)
            ->group(function () {

                Route::get('/profile', 'adminProfile')
                    ->name('admin.profile');

                Route::post('/profile/update', 'updateAdminProfile')
                    ->name('admin.profile.update');

                Route::get('/change-password', 'changePassword')
                    ->name('admin.change-password');

                Route::post('/change-password', 'updatePassword')
                    ->name('admin.change-password.update');
            });

        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('admin.logout');
        
    });