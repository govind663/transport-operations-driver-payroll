<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserHistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
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
     * Display forgot password form.
     */
    public function showLinkRequestForm(): View
    {
        return view('backend.auth.passwords.email');
    }

    /**
     * Send password reset email.
     */
    public function sendResetLinkEmail(
        Request $request
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE EMAIL
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email,deleted_at,NULL',
            ],
        ], [
            'email.required' => 'Email ID is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email does not exist.',
        ]);

        $email = $validated['email'];

        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */

        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email does not exist.');
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE RESET TOKEN
        |--------------------------------------------------------------------------
        |
        | Replace an existing token for this email instead of rejecting
        | the user because a previous reset request exists.
        |
        */

        $token = Str::random(64);

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEND RESET EMAIL
        |--------------------------------------------------------------------------
        */

        try {
            Mail::send(
                'backend.auth.verify',
                ['token' => $token],
                function ($message) use ($email) {
                    $message->from(
                        'info@pixelpearltechnologies.com',
                        'TRADE BO'
                    );

                    $message->to($email);

                    $message->subject(
                        'TRADE BO | Password Reset Request'
                    );
                }
            );
        } catch (\Throwable $e) {
            report($e);

            // Remove the unused token if email delivery fails.
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return back()
                ->withInput($request->only('email'))
                ->with(
                    'error',
                    'Unable to send the password reset email. Please try again later.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STORE ACTIVITY HISTORY
        |--------------------------------------------------------------------------
        */

        try {
            $this->historyService->store(
                $user->id,
                'password_reset_request'
            );
        } catch (\Throwable $e) {
            // History logging should not invalidate a successful email request.
            report($e);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'message',
            'Password reset link has been sent to your email.'
        );
    }
}
