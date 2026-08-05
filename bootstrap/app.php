<?php

use App\Http\Middleware\OptimizeImagesMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\PreventCitizenBackHistoryMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(
    basePath: dirname(__DIR__)
)

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Web Middleware
        |--------------------------------------------------------------------------
        |
        | These middleware are applied to all routes defined in
        | routes/web.php.
        |
        */

        $middleware->web(append: [

            /*
            |--------------------------------------------------------------------------
            | Laravel Core Middleware
            |--------------------------------------------------------------------------
            */

            \Illuminate\Cookie\Middleware\EncryptCookies::class,

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            \Illuminate\Session\Middleware\StartSession::class,

            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            \Illuminate\Routing\Middleware\SubstituteBindings::class,


            /*
            |--------------------------------------------------------------------------
            | Image Optimization
            |--------------------------------------------------------------------------
            |
            | Handles:
            | - Frontend HTML images
            | - Admin HTML images
            | - Image metadata
            | - Image optimization logs
            |
            | The middleware itself decides which requests to skip.
            |
            */

            OptimizeImagesMiddleware::class,


            /*
            |--------------------------------------------------------------------------
            | Browser / Cache Protection
            |--------------------------------------------------------------------------
            */

            PreventBackHistoryMiddleware::class,

            PreventCitizenBackHistoryMiddleware::class,


            /*
            |--------------------------------------------------------------------------
            | Authentication Logic
            |--------------------------------------------------------------------------
            */

            RedirectIfAuthenticatedCustom::class,
        ]);
    })

    /*
    |--------------------------------------------------------------------------
    | Exception Handling
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | Exception Reporting
        |--------------------------------------------------------------------------
        */

        $exceptions->report(function (\Throwable $e): void {

            /*
            |--------------------------------------------------------------------------
            | Ignore Expected Exceptions
            |--------------------------------------------------------------------------
            */

            if (
                $e instanceof ValidationException ||
                $e instanceof AuthenticationException ||
                $e instanceof NotFoundHttpException ||
                $e instanceof AccessDeniedHttpException ||
                $e instanceof TokenMismatchException
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Log Unexpected Application Errors
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Application Error: ' . $e->getMessage(),
                [
                    'exception' => get_class($e),
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                ]
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Exception Rendering
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            \Throwable $e,
            Request $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | Validation Exception
            |--------------------------------------------------------------------------
            */

            if ($e instanceof ValidationException) {

                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | Authentication Exception
            |--------------------------------------------------------------------------
            */

            if ($e instanceof AuthenticationException) {

                /*
                |--------------------------------------------------------------------------
                | JSON / AJAX Request
                |--------------------------------------------------------------------------
                */

                if ($request->expectsJson()) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Unauthenticated.',
                    ], 401);
                }


                /*
                |--------------------------------------------------------------------------
                | Normal Web Request
                |--------------------------------------------------------------------------
                */

                return redirect()
                    ->route('admin.login')
                    ->with(
                        'warning',
                        'Please login first to access this page.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | 404 - Not Found
            |--------------------------------------------------------------------------
            */

            if ($e instanceof NotFoundHttpException) {

                if ($request->expectsJson()) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Resource not found.',
                    ], 404);
                }

                return response()
                    ->view('errors.404', [], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | 403 - Access Denied
            |--------------------------------------------------------------------------
            */

            if ($e instanceof AccessDeniedHttpException) {

                if ($request->expectsJson()) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Access denied.',
                    ], 403);
                }

                return response()
                    ->view('errors.403', [], 403);
            }


            /*
            |--------------------------------------------------------------------------
            | 419 - CSRF / Session Expired
            |--------------------------------------------------------------------------
            */

            if ($e instanceof TokenMismatchException) {

                if ($request->expectsJson()) {

                    return response()->json([
                        'status'  => false,
                        'message' => 'Session expired. Please login again.',
                    ], 419);
                }

                return redirect()
                    ->route('admin.login')
                    ->with(
                        'warning',
                        'Session expired. Please login again.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | JSON / AJAX - Generic Error
            |--------------------------------------------------------------------------
            */

            if ($request->expectsJson()) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Something went wrong.',
                ], 500);
            }


            /*
            |--------------------------------------------------------------------------
            | Normal Web - 500
            |--------------------------------------------------------------------------
            */

            return response()
                ->view('errors.500', [], 500);
        });
    })

    /*
    |--------------------------------------------------------------------------
    | Create Application
    |--------------------------------------------------------------------------
    */

    ->create();