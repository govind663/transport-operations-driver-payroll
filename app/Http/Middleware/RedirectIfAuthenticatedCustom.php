<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedCustom
{
    /**
     * Guest-only routes where authenticated users
     * should be redirected to the admin dashboard.
     *
     * @var array<int, string>
     */
    protected array $guestRoutes = [
        'admin.login',
        'admin.register',
        'admin.forget-password.request',
        'admin.password.reset',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        ?string $guard = 'web'
    ): Response {
        $guard = $guard ?: 'web';

        /*
         * Redirect authenticated users only when they
         * access the specified guest authentication routes.
         */
        if (
            Auth::guard($guard)->check()
            && $request->route()
            && $request->routeIs($this->guestRoutes)
        ) {
            return redirect()
                ->route('admin.dashboard')
                ->with('message', 'You are already logged in!');
        }

        return $next($request);
    }


    /**
     * Get the configured guest routes.
     */
    protected function getGuestRoutes(): array
    {
        return $this->guestRoutes;
    }

    }
