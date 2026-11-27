<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistoryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | HEADER VALUE CLEANER
        |--------------------------------------------------------------------------
        */

        $clean = static function ($value): string {
            return trim(
                str_replace(
                    ["\r", "\n"],
                    ' ',
                    (string) $value
                )
            );
        };

        /*
        |--------------------------------------------------------------------------
        | CONTENT SECURITY POLICY
        |--------------------------------------------------------------------------
        */

        $cspDirectives = [
            "default-src 'self'",

            "base-uri 'self'",

            "frame-ancestors 'self'",

            "object-src 'none'",

            "img-src 'self' data: https:",

            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net",

            "connect-src 'self' https://www.google.com https://www.gstatic.com https://maps.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",

            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://ajax.googleapis.com https://www.gstatic.com https://maps.googleapis.com https://www.google.com https://unpkg.com https://cdn.jsdelivr.net",

            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",

            "frame-src 'self' https://www.google.com https://www.gstatic.com https://maps.google.com https://maps.googleapis.com",
        ];

        /*
        |--------------------------------------------------------------------------
        | HTTPS RESOURCE UPGRADE
        |--------------------------------------------------------------------------
        |
        | Upgrade insecure HTTP resources to HTTPS.
        | Applied in production even behind a reverse proxy.
        |
        */

        if (
            app()->environment('production') ||
            $request->isSecure()
        ) {
            $cspDirectives[] = 'upgrade-insecure-requests';
        }

        $contentSecurityPolicy = implode(
            '; ',
            array_map($clean, $cspDirectives)
        );

        /*
        |--------------------------------------------------------------------------
        | SECURITY HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',

            'X-Content-Type-Options' => 'nosniff',

            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            'Content-Security-Policy' => $contentSecurityPolicy,

            'Permissions-Policy' => 'camera=(), microphone=()',
        ];

        /*
        |--------------------------------------------------------------------------
        | HTTPS STRICT TRANSPORT SECURITY
        |--------------------------------------------------------------------------
        |
        | HSTS is sent only over HTTPS requests.
        | Ensure all subdomains support HTTPS before using includeSubDomains.
        |
        */

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] =
                'max-age=31536000; includeSubDomains; preload';
        }

        /*
        |--------------------------------------------------------------------------
        | CACHE STRATEGY
        |--------------------------------------------------------------------------
        */

        if (
            $request->is('backend/assets/*') ||
            $request->is('build/*')
        ) {
            $headers['Cache-Control'] =
                'public, max-age=31536000, immutable';

        } elseif (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard') ||
            $request->is('profile')
        ) {
            $headers['Cache-Control'] =
                'no-store, no-cache, must-revalidate, max-age=0';

            $headers['Pragma'] = 'no-cache';

            $headers['Expires'] = '0';

        } else {
            $headers['Cache-Control'] =
                'public, max-age=86400';
        }

        /*
        |--------------------------------------------------------------------------
        | PERFORMANCE HEADERS
        |--------------------------------------------------------------------------
        */

        $headers['X-DNS-Prefetch-Control'] = 'on';

        /*
        |--------------------------------------------------------------------------
        | SEO CONTROL
        |--------------------------------------------------------------------------
        */

        if (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard')
        ) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';

        } else {
            $headers['X-Robots-Tag'] = 'index, follow';
        }

        /*
        |--------------------------------------------------------------------------
        | APPLY RESPONSE HEADERS
        |--------------------------------------------------------------------------
        */

        foreach ($headers as $key => $value) {
            if ($value !== null && $value !== '') {
                $response->headers->set(
                    $key,
                    $clean($value),
                    true
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN RESPONSE
        |--------------------------------------------------------------------------
        */

        return $response;
    }
}
