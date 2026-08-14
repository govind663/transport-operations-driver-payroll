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
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\ClientManagementController;
use App\Http\Controllers\backend\DriverManagementController;
use App\Http\Controllers\backend\DutyAssignmentController;
use App\Http\Controllers\backend\DutySlipController;
use App\Http\Controllers\backend\TravelRequestController;
use App\Http\Controllers\backend\VehicleCategoryController;
use App\Http\Controllers\backend\VehicleManagementController;
use App\Http\Controllers\backend\VehicleTypeController;
use App\Http\Controllers\backend\WorkingSheetController;
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
| Frontend Home Redirect
|--------------------------------------------------------------------------
|
| /
|
| Logged in → Admin Dashboard
| Guest      → Admin Login
|
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
|
| /login
|
| Logged in → Admin Dashboard
| Guest      → Admin Login
|
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
|
| These routes are accessible only when the admin is NOT authenticated.
|
| OptimizeImagesMiddleware is explicitly called here.
|
*/
Route::prefix('')
    ->middleware([
        OptimizeImagesMiddleware::class,
        RedirectIfAuthenticatedCustom::class,
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Login
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/',
            [LoginController::class, 'login']
        )->name('admin.login');

        Route::post(
            '/login',
            [LoginController::class, 'authenticate']
        )->name('admin.login.store');


        /*
        |--------------------------------------------------------------------------
        | Admin Register
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/register',
            [RegisterController::class, 'register']
        )->name('admin.register');

        Route::post(
            '/register',
            [RegisterController::class, 'store']
        )->name('admin.register.store');


        /*
        |--------------------------------------------------------------------------
        | Forgot Password
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/forgot-password',
            [ForgotPasswordController::class, 'showLinkRequestForm']
        )->name('admin.password.request');

        Route::post(
            '/forgot-password',
            [ForgotPasswordController::class, 'sendResetLinkEmail']
        )->name('admin.password.email');


        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/reset-password/{token}',
            [ResetPasswordController::class, 'showResetForm']
        )->name('admin.password.reset');

        Route::post(
            '/reset-password',
            [ResetPasswordController::class, 'updatePassword']
        )->name('admin.password.update');

    });


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
|
| These routes require authenticated admin user.
|
| OptimizeImagesMiddleware is explicitly called here as well.
|
*/
Route::prefix('admin')
    ->middleware([
        OptimizeImagesMiddleware::class,
        'auth:web',
        PreventBackHistoryMiddleware::class,
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/dashboard',
            [BackendHomeController::class, 'adminHome']
        )->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::resource('users', UserController::class);

        /*
        |--------------------------------------------------------------------------
        | Client Management
        |--------------------------------------------------------------------------
        */
        Route::resource('client-management', ClientManagementController::class);

        /*
        |--------------------------------------------------------------------------
        | Driver Management
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'driver-management',
            DriverManagementController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Vehicle Categories
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'vehicle-categories',
            VehicleCategoryController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Vehicle Types
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'vehicle-types',
            VehicleTypeController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Vehicle Management
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'vehicle-management',
            VehicleManagementController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Travel Requests
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'travel-requests',
            TravelRequestController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Duty Assignments
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'duty-assignments',
            DutyAssignmentController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Duty Slips
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'duty-slips',
            DutySlipController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Working Sheets
        |--------------------------------------------------------------------------
        */
        Route::resource(
            'working-sheets',
            WorkingSheetController::class
        );

        /*
        |--------------------------------------------------------------------------
        | Profile & Account
        |--------------------------------------------------------------------------
        */
        Route::controller(BackendHomeController::class)
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Profile
                |--------------------------------------------------------------------------
                */
                Route::get(
                    '/profile',
                    'adminProfile'
                )->name('admin.profile');


                /*
                |--------------------------------------------------------------------------
                | Update Profile
                |--------------------------------------------------------------------------
                */
                Route::post(
                    '/profile/update',
                    'updateAdminProfile'
                )->name('admin.profile.update');


                /*
                |--------------------------------------------------------------------------
                | Change Password
                |--------------------------------------------------------------------------
                */
                Route::get(
                    '/change-password',
                    'changePassword'
                )->name('admin.change-password');


                /*
                |--------------------------------------------------------------------------
                | Update Password
                |--------------------------------------------------------------------------
                */
                Route::post(
                    '/change-password',
                    'updatePassword'
                )->name('admin.change-password.update');

            });


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */
        Route::post(
            '/logout',
            [LoginController::class, 'logout']
        )->name('admin.logout');

    });
