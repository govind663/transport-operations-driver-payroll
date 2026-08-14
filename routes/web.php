<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
Route::prefix('admin')
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

/*
|--------------------------------------------------------------------------
| Temporary Server Utility Routes
|--------------------------------------------------------------------------
| IMPORTANT:
| Ye routes cPanel / shared hosting par temporary deployment
| aur maintenance ke liye hain.
|
| Production setup complete hone ke baad inhe
| authentication/secret key se protect ya remove kar dena.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 01. SERVER CHECK
|--------------------------------------------------------------------------
*/
Route::get('/server-check', function () {

    return response()->json([
        'success' => true,
        'message' => 'Laravel server is working fine.',
        'environment' => app()->environment(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
    ]);

});

/*
|--------------------------------------------------------------------------
| 02. COMPOSER DUMP AUTOLOAD
|--------------------------------------------------------------------------
*/
Route::get('/composer-dump-autoload', function () {

    try {

        $composer = '/opt/cpanel/composer/bin/composer';

        if (!file_exists($composer)) {
            return response()->json([
                'success' => false,
                'message' => 'Composer executable not found.',
                'path' => $composer,
            ], 500);
        }

        $command = 'cd '
            . escapeshellarg(base_path())
            . ' && '
            . escapeshellarg($composer)
            . ' dump-autoload --optimize 2>&1';

        $output = shell_exec($command);

        return response()->json([
            'success' => true,
            'message' => 'Composer autoload generated successfully.',
            'output' => $output,
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Composer dump-autoload failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});

/*
|--------------------------------------------------------------------------
| 03. DATABASE MIGRATION
|--------------------------------------------------------------------------
*/
Route::get('/migrate', function () {

    try {

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Database migrations completed successfully.',
            'output' => trim(Artisan::output()),
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Database migration failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});

/*
|--------------------------------------------------------------------------
| 04. CLEAR ALL LARAVEL CACHE
|--------------------------------------------------------------------------
*/
Route::get('/optimize-clear', function () {

    try {

        Artisan::call('optimize:clear');

        return response()->json([
            'success' => true,
            'message' => 'All Laravel caches cleared successfully.',
            'output' => trim(Artisan::output()),
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Laravel cache clear failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});

/*
|--------------------------------------------------------------------------
| 05. OPTIMIZE LARAVEL
|--------------------------------------------------------------------------
*/
Route::get('/optimize', function () {

    try {

        Artisan::call('optimize');

        return response()->json([
            'success' => true,
            'message' => 'Laravel optimized successfully.',
            'output' => trim(Artisan::output()),
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Laravel optimization failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});

/*
|--------------------------------------------------------------------------
| 06. MIGRATION STATUS
|--------------------------------------------------------------------------
*/
Route::get('/migrate-status', function () {

    try {

        Artisan::call('migrate:status');

        return response()->json([
            'success' => true,
            'message' => 'Migration status fetched successfully.',
            'output' => trim(Artisan::output()),
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch migration status.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});

/*
|--------------------------------------------------------------------------
| Composer Install
|--------------------------------------------------------------------------
| Runs: composer install --optimize-autoloader
|--------------------------------------------------------------------------
*/
Route::get('/composer-install', function () {

    try {

        $composer = '/opt/cpanel/composer/bin/composer';

        if (!file_exists($composer)) {
            return response()->json([
                'success' => false,
                'message' => 'Composer executable not found.',
                'path' => $composer,
            ], 500);
        }

        $command = 'cd '
            . escapeshellarg(base_path())
            . ' && '
            . escapeshellarg($composer)
            . ' install --no-dev --optimize-autoloader 2>&1';

        $output = shell_exec($command);

        return response()->json([
            'success' => true,
            'message' => 'Composer install completed.',
            'output' => $output,
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Composer install failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});


/*
|--------------------------------------------------------------------------
| Composer Update
|--------------------------------------------------------------------------
| Runs: composer update --no-dev --optimize-autoloader
|--------------------------------------------------------------------------
*/
Route::get('/composer-update', function () {

    try {

        $composer = '/opt/cpanel/composer/bin/composer';

        if (!file_exists($composer)) {
            return response()->json([
                'success' => false,
                'message' => 'Composer executable not found.',
                'path' => $composer,
            ], 500);
        }

        $command = 'cd '
            . escapeshellarg(base_path())
            . ' && '
            . escapeshellarg($composer)
            . ' update --no-dev --optimize-autoloader 2>&1';

        $output = shell_exec($command);

        return response()->json([
            'success' => true,
            'message' => 'Composer update completed.',
            'output' => $output,
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'Composer update failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Server error.',
        ], 500);

    }

});