<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCitizenBackHistoryMiddleware
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
        | CONTENT SECURITY POLICY DIRECTIVES
        |--------------------------------------------------------------------------
        |
        | form-action is intentionally not restricted.
        | Other CSP security directives remain active.
        |
        */

        $cspDirectives = [
            "default-src 'self'",

            "base-uri 'self'",

            "frame-ancestors 'self'",

            "object-src 'none'",

            "img-src 'self' data: https:",

            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net",

            "connect-src 'self' https://www.google.com https://www.gstatic.com https://maps.googleapis.com https://unpkg.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",

            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://ajax.googleapis.com https://www.gstatic.com https://maps.googleapis.com https://www.google.com https://unpkg.com https://cdn.jsdelivr.net",

            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",

            "frame-src 'self' https://www.google.com https://www.gstatic.com https://maps.google.com https://maps.googleapis.com",
        ];

        /*
        |--------------------------------------------------------------------------
        | HTTPS ONLY CSP DIRECTIVE
        |--------------------------------------------------------------------------
        */

        if ($request->isSecure()) {
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
            // Clickjacking protection.
            'X-Frame-Options' => 'SAMEORIGIN',

            // MIME sniffing protection.
            'X-Content-Type-Options' => 'nosniff',

            // Legacy browser XSS protection.
            'X-XSS-Protection' => '1; mode=block',

            // Referrer policy.
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // Browser permissions.
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(self)',

            // Content Security Policy.
            'Content-Security-Policy' => $contentSecurityPolicy,
        ];

        /*
        |--------------------------------------------------------------------------
        | HTTPS STRICT TRANSPORT SECURITY
        |--------------------------------------------------------------------------
        */

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] =
                'max-age=31536000; includeSubDomains; preload';
        }

        /*
        |--------------------------------------------------------------------------
        | SMART CACHE STRATEGY
        |--------------------------------------------------------------------------
        */

        if (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard') ||
            $request->is('profile')
        ) {
            // Sensitive pages.
            $headers['Cache-Control'] =
                'no-store, no-cache, must-revalidate, max-age=0';

            $headers['Pragma'] = 'no-cache';

            $headers['Expires'] = '0';

        } elseif (
            $request->is('backend/assets/*') ||
            $request->is('build/*')
        ) {
            // Static assets.
            $headers['Cache-Control'] =
                'public, max-age=31536000, immutable';

        } else {
            // Public pages.
            $headers['Cache-Control'] =
                'public, max-age=86400';
        }

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
        | PERFORMANCE HEADERS
        |--------------------------------------------------------------------------
        */

        $headers['X-DNS-Prefetch-Control'] = 'on';

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
