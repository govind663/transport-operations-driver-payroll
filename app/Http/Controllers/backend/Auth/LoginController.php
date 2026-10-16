<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\LoginRequest;
use App\Services\UserHistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * User history service.
     */
    protected UserHistoryService $historyService;

    /**
     * Dependency injection.
     */
    public function __construct(UserHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Display login page or redirect authenticated users.
     */
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.auth.login');
    }

    /**
     * Authenticate user and record login history.
     */
    public function authenticate(
        LoginRequest $request
    ): RedirectResponse {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $remember = $request->boolean('remember');

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email address or password.');
        }

        // Regenerate session after successful authentication.
        $request->session()->regenerate();

        // Preserve existing login history behavior.
        try {
            $this->historyService->store(
                Auth::guard('web')->id(),
                'login'
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('message', 'You are successfully logged in!');
    }

    /**
     * Record logout history and log out the current user.
     */
    public function logout(): RedirectResponse
    {
        $guard = Auth::guard('web');

        // Save history before clearing the authenticated session.
        if ($guard->check()) {
            try {
                $this->historyService->store(
                    $guard->id(),
                    'logout'
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Log out using the same guard used for login.
        $guard->logout();

        // Invalidate the session and regenerate the CSRF token.
        Session::invalidate();
        Session::regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('message', 'You are logout successfully.');
    }
}
