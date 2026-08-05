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
            'email'    => $request->email,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {

            // Regenerate Session
            $request->session()->regenerate();

            // Store Login History
            $this->historyService->store(Auth::id(), 'login');

            return redirect()
                ->route('admin.dashboard')
                ->with('message', 'You are successfully logged in!');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email address or password.');
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