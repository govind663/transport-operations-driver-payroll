<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\RegisterRequest;
use App\Models\User;
use App\Services\UserHistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
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
     * Display registration page.
     */
    public function register(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.auth.register');
    }

    /**
     * Create a new user account.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make(
            $request->input('password')
        );

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | SET CREATOR
        |--------------------------------------------------------------------------
        |
        | Preserve existing behavior: a self-registered user is recorded
        | as their own creator.
        |
        */

        $user->update([
            'created_by' => $user->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE REGISTRATION HISTORY
        |--------------------------------------------------------------------------
        |
        | History logging failure should not undo successful registration.
        |
        */

        try {
            $this->historyService->store(
                $user->id,
                'register'
            );
        } catch (\Throwable $e) {
            report($e);
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.login')
            ->with('message', 'You are registered successfully.');
    }
}
