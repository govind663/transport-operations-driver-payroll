<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Redirect destination after successful verification.
     */
    protected $redirectTo = '/home';

    /**
     * Configure verification middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('signed')
            ->only('verify');

        $this->middleware('throttle:6,1')
            ->only('verify', 'resend');
    }

    /**
     * Verify the user's email address.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath())
                ->with('message', 'Your email address has already been verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return redirect($this->redirectPath())
            ->with('message', 'Your email address has been verified successfully.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath())
                ->with('message', 'Your email address is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with(
            'message',
            'A fresh email verification link has been sent to your email address.'
        );
    }
}
