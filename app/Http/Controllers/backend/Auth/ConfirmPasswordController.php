<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Redirect destination after password confirmation.
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Configure authentication middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect authenticated users to the configured dashboard
     * when password confirmation succeeds.
     */
    protected function redirectTo(): string
    {
        return route('admin.dashboard');
    }
}
