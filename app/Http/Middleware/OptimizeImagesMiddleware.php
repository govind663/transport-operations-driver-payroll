<?php

namespace App\Http\Middleware;

use App\Services\ImageOptimizationLogger;
use App\Services\ImageOptimizationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OptimizeImagesMiddleware
{
    public function __construct(
        protected ImageOptimizationService $optimizationService,
        protected ImageOptimizationLogger $logger
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | First generate the response
        |--------------------------------------------------------------------------
        */
        $response = $next($request);

        try {

            /*
            |--------------------------------------------------------------------------
            | SHOULD RUN
            |--------------------------------------------------------------------------
            */
            if (!$this->shouldRun($request, $response)) {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | GET RESPONSE HTML
            |--------------------------------------------------------------------------
            */
            $html = $response->getContent();

            if (!is_string($html) || trim($html) === '') {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | PERFORMANCE PROTECTION
            |--------------------------------------------------------------------------
            */
            if (strlen($html) > 300000) {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | NO IMAGE = NOTHING TO PROCESS
            |--------------------------------------------------------------------------
            */
            if (stripos($html, '<img') === false) {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL HTML BACKUP
            |--------------------------------------------------------------------------
            */
            $originalHtml = $html;

            /*
            |--------------------------------------------------------------------------
            | IMAGE OPTIMIZATION
            |--------------------------------------------------------------------------
            */
            $optimizedHtml = $this->optimizationService->optimize(
                $html,
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | VALIDATE OPTIMIZED HTML
            |--------------------------------------------------------------------------
            */
            if (
                is_string($optimizedHtml) &&
                $optimizedHtml !== '' &&
                strlen($optimizedHtml) > 1000 &&
                str_contains(
                    strtolower($optimizedHtml),
                    '<html'
                ) &&
                str_contains(
                    strtolower($optimizedHtml),
                    '</html>'
                )
            ) {
                $response->setContent($optimizedHtml);
            } else {

                /*
                |--------------------------------------------------------------------------
                | FALLBACK
                |--------------------------------------------------------------------------
                */
                $response->setContent($originalHtml);
            }

            /*
            |--------------------------------------------------------------------------
            | CACHE CONTROL
            |--------------------------------------------------------------------------
            */
            if (!$response->headers->has('Cache-Control')) {
                $response->headers->set(
                    'Cache-Control',
                    'public, max-age=300'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PERSIST IMAGE LOGS
            |--------------------------------------------------------------------------
            */
            $this->logger->persist($request);

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | NEVER BREAK APPLICATION UI
            |--------------------------------------------------------------------------
            */
            Log::error('OptimizeImagesMiddleware Failed', [
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'method' => $request->method(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | SYSTEM FAILURE LOG
            |--------------------------------------------------------------------------
            */
            $this->logger->logSystemFailure(
                $request,
                $e
            );

            /*
            |--------------------------------------------------------------------------
            | RETURN ORIGINAL RESPONSE
            |--------------------------------------------------------------------------
            */
            return $response;
        }

        return $response;
    }


    /**
     * Determine whether image optimization should run.
     *
     * IMPORTANT:
     * No admin/login/register/password route is excluded here.
     *
     * Every GET HTML page is allowed.
     */
    protected function shouldRun(
        Request $request,
        Response $response
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | GET REQUESTS ONLY
        |--------------------------------------------------------------------------
        |
        | Image optimization modifies HTML responses.
        |
        */
        if (!$request->isMethod('GET')) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | ONLY HTML RESPONSE
        |--------------------------------------------------------------------------
        */
        $contentType = strtolower(
            (string) $response->headers->get(
                'Content-Type',
                ''
            )
        );

        if (!str_contains($contentType, 'text/html')) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | ALL GET HTML ROUTES ARE ALLOWED
        |--------------------------------------------------------------------------
        |
        | Including:
        |
        | /admin
        | /admin/dashboard
        | /admin/profile
        | /admin/register
        | /admin/forgot-password
        | /admin/reset-password/...
        | /login
        | /register
        | /password/...
        | etc.
        |
        */

        return true;
    }
}