<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\LoginRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Services\UserHistoryService;

class LoginController extends Controller
{
    protected $historyService;

    // Dependency Injection
    public function __construct(UserHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('backend.auth.login');
        }
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember_token');

        if (Auth::attempt($credentials, $remember)) {

            // Session Regenerate
            $request->session()->regenerate();

            // Login History
            $this->historyService->store(Auth::id(), 'login');

            return redirect()
                ->route('admin.dashboard')
                ->with('message', 'You are successfully logged in!');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        // Store logout history before logout
        if (Auth::check()) {
            $this->historyService->store(Auth::id(), 'logout');
        }

        // Clear session
        Session::flush();

        // Logout
        Auth::logout();

        return redirect()
            ->route('admin.login')
            ->with('message', 'You are logout Successfully.');
    }
}