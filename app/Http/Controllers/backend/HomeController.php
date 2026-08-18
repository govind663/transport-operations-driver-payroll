<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected DashboardService $dashboardService;


    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }


    /**
     * =========================================================
     * DASHBOARD
     * =========================================================
     */
    public function adminHome()
    {
        $user = Auth::user();

        $dashboard = $this->dashboardService
            ->getDashboardData($user);

        return view('backend.home',
            compact('dashboard')
        );
    }


    public function changePassword(Request $request)
    {
        return view('backend.auth.change-password');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'Current Password is required',
            'password.required' => 'New Password is required',
            'password.confirmed' => 'Password and Confirm Password does not match',
            'password.min' => 'Password must be at least 8 characters.',
            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.min' => 'Confirm Password must be at least 8 characters.',
        ]);


        if (!Hash::check(
            $request->current_password,
            Auth::user()->password
        )) {
            return back()->with(
                'error',
                "Old Password Doesn't match!"
            );
        }


        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->password)
        ]);


        return redirect()
            ->route('admin.dashboard')
            ->with(
                'message',
                'Password changed successfully!'
            );
    }


    /**
     * =========================================================
     * ADMIN PROFILE
     * =========================================================
     */
    public function adminProfile()
    {
        return view('backend.auth.profile');
    }


    /**
     * =========================================================
     * UPDATE ADMIN PROFILE
     * =========================================================
     */
    public function updateAdminProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'phone' => 'required|string|max:10',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.required' => 'Phone number is required',
            'phone.max' => 'Phone number must not exceed 15 characters',
            'profile_image.image' => 'The profile image must be an image file',
            'profile_image.mimes' => 'Allowed formats: jpeg, png, jpg, webp',
            'profile_image.max' => 'File size cannot exceed 2MB',
        ]);


        $user = User::findOrFail(Auth::user()->id);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->updated_by = Auth::user()->id;
        $user->updated_at = now();


        if ($request->hasFile('profile_image')) {

            if ($user->profile_image) {

                $oldPath = public_path(
                    'backend/assets/uploads/profile/' .
                    $user->profile_image
                );

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }


            $image = $request->file('profile_image');

            $extension = $image->getClientOriginalExtension();

            $newName = time() . rand(10, 999) . '.' . $extension;

            $image->move(
                public_path('backend/assets/uploads/profile'),
                $newName
            );

            $user->profile_image = $newName;
        }


        $user->save();


        return redirect()
            ->route('admin.profile')
            ->with(
                'message',
                'Profile updated successfully!'
            );
    }
}