<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserHistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * User history service.
     */
    protected UserHistoryService $historyService;

    /**
     * Reset token validity in minutes.
     */
    protected int $tokenExpiryMinutes = 60;

    /**
     * Dependency injection.
     */
    public function __construct(UserHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Display password reset form after validating token.
     */
    public function showResetForm(string $token): View|RedirectResponse
    {
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (! $tokenData) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Invalid or expired password reset token.');
        }

        if (
            empty($tokenData->created_at)
            || now()->greaterThan(
                \Illuminate\Support\Carbon::parse($tokenData->created_at)
                    ->addMinutes($this->tokenExpiryMinutes)
            )
        ) {
            DB::table('password_reset_tokens')
                ->where('email', $tokenData->email)
                ->delete();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Password reset token has expired. Please request a new one.');
        }

        $userExists = User::query()
            ->where('email', $tokenData->email)
            ->exists();

        if (! $userExists) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Invalid password reset request.');
        }

        return view('backend.auth.passwords.reset', [
            'token' => $token,
            'email' => $tokenData->email,
        ]);
    }

    /**
     * Validate reset request and update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE INPUT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],

            'password_confirmation' => [
                'required',
                'string',
            ],
        ], [
            'token.required' => 'Password reset token is required.',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',

            'password_confirmation.required' => 'Password confirmation is required.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFY TOKEN AND EMAIL
        |--------------------------------------------------------------------------
        */

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('token', $validated['token'])
            ->first();

        if (! $tokenData) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid password reset token.');
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK TOKEN EXPIRY
        |--------------------------------------------------------------------------
        */

        if (
            empty($tokenData->created_at)
            || now()->greaterThan(
                \Illuminate\Support\Carbon::parse($tokenData->created_at)
                    ->addMinutes($this->tokenExpiryMinutes)
            )
        ) {
            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->delete();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Password reset token has expired. Please request a new one.');
        }

        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */

        $user = User::query()
            ->where('email', $validated['email'])
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid password reset request.');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD AND DELETE TOKEN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($user, $validated) {
            $user->password = Hash::make($validated['password']);
            $user->save();

            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->delete();
        });

        /*
        |--------------------------------------------------------------------------
        | STORE ACTIVITY HISTORY
        |--------------------------------------------------------------------------
        */

        try {
            $this->historyService->store(
                $user->id,
                'password_reset'
            );
        } catch (\Throwable $e) {
            report($e);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.login')
            ->with(
                'message',
                'Your password has been changed successfully. You can now log in with your new password.'
            );
    }
}
