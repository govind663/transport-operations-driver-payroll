<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Throwable;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard Service
    |--------------------------------------------------------------------------
    */

    protected DashboardService $dashboardService;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /*
    |--------------------------------------------------------------------------
    | Role-Based Dashboard
    |--------------------------------------------------------------------------
    */

    public function adminHome(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 401);

        $dashboardStats = $this->dashboardService
            ->getDashboardData($user);

        return view(
            'backend.home',
            compact('dashboardStats')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Change Password Page
    |--------------------------------------------------------------------------
    */

    public function changePassword(Request $request)
    {
        abort_unless($request->user(), 401);

        return view('backend.auth.change-password');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 401);

        /*
        |--------------------------------------------------------------------------
        | Validate Password Fields
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],

            'password_confirmation' => [
                'required',
                'string',
                'min:8',
            ],
        ], [
            'current_password.required' =>
                'Current Password is required.',

            'password.required' =>
                'New Password is required.',

            'password.min' =>
                'Password must be at least 8 characters.',

            'password.confirmed' =>
                'Password and Confirm Password do not match.',

            'password.different' =>
                'New password must be different from the current password.',

            'password_confirmation.required' =>
                'Confirm Password is required.',

            'password_confirmation.min' =>
                'Confirm Password must be at least 8 characters.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify Current Password
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $validated['current_password'],
            $user->password
        )) {
            return back()
                ->withErrors([
                    'current_password' =>
                        "The current password doesn't match.",
                ])
                ->with('error', "Old Password Doesn't match!");
        }

        /*
        |--------------------------------------------------------------------------
        | Save New Password
        |--------------------------------------------------------------------------
        */

        $user->password = Hash::make($validated['password']);
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.dashboard')
            ->with('message', 'Password changed successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Page
    |--------------------------------------------------------------------------
    */

    public function adminProfile(Request $request)
    {
        abort_unless($request->user(), 401);

        return view('backend.auth.profile');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function updateAdminProfile(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 401);

        /*
        |--------------------------------------------------------------------------
        | Validate Profile Fields
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->getKey()),
            ],

            'phone' => [
                'required',
                'string',
                'max:15',
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ], [
            'name.required' =>
                'Full Name is required.',

            'email.required' =>
                'Email is required.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email is already taken.',

            'phone.required' =>
                'Phone number is required.',

            'phone.max' =>
                'Phone number must not exceed 15 characters.',

            'profile_image.image' =>
                'The profile image must be an image file.',

            'profile_image.mimes' =>
                'Allowed formats: jpeg, png, jpg, webp.',

            'profile_image.max' =>
                'File size cannot exceed 2MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Current User
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail($user->getKey());

        $profileDirectory = public_path(
            'backend/assets/uploads/profile'
        );

        $oldImageName = $user->profile_image;

        $newImageName = null;
        $newImagePath = null;

        /*
        |--------------------------------------------------------------------------
        | Upload New Image Before Replacing Existing Image
        |--------------------------------------------------------------------------
        */

        try {
            if ($request->hasFile('profile_image')) {
                if (!File::isDirectory($profileDirectory)) {
                    File::makeDirectory(
                        $profileDirectory,
                        0755,
                        true
                    );
                }

                $image = $request->file('profile_image');

                $extension = strtolower(
                    $image->extension() ?: 'jpg'
                );

                $newImageName = Str::uuid()
                    . '.'
                    . $extension;

                $image->move(
                    $profileDirectory,
                    $newImageName
                );

                $newImagePath = $profileDirectory
                    . DIRECTORY_SEPARATOR
                    . $newImageName;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Profile in Database Transaction
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $user,
                $validated,
                $newImageName
            ) {
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->phone = $validated['phone'];
                $user->updated_by = $user->getKey();

                if ($newImageName !== null) {
                    $user->profile_image = $newImageName;
                }

                $user->save();
            });
        } catch (Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Clean Up Newly Uploaded Image on Failure
            |--------------------------------------------------------------------------
            */

            if (
                $newImagePath !== null
                && is_file($newImagePath)
            ) {
                @unlink($newImagePath);
            }

            report($exception);

            return back()
                ->withInput($request->except('profile_image'))
                ->with(
                    'error',
                    'Profile could not be updated. Please try again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Previous Image Only After Successful Database Update
        |--------------------------------------------------------------------------
        */

        if (
            $newImageName !== null
            && !empty($oldImageName)
        ) {
            // Restrict deletion to a filename inside the profile directory.
            $safeOldImageName = basename($oldImageName);

            if ($safeOldImageName === $oldImageName) {
                $oldImagePath = $profileDirectory
                    . DIRECTORY_SEPARATOR
                    . $safeOldImageName;

                if (
                    $oldImagePath !== $newImagePath
                    && is_file($oldImagePath)
                ) {
                    @unlink($oldImagePath);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.profile')
            ->with('message', 'Profile updated successfully!');
    }
}
