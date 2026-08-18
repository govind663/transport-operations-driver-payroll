<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function __construct(
        DashboardService $dashboardService
    ) {
        $this->dashboardService = $dashboardService;
    }


    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    public function adminHome()
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $dashboardStats =
            $this->dashboardService
                ->getDashboardData($user);


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.home',
            compact(
                'dashboardStats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

    public function changePassword(Request $request)
    {
        return view(
            'backend.auth.change-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'current_password' =>
                'required',

            'password' =>
                'required|string|min:8|confirmed',

            'password_confirmation' =>
                'required|string|min:8',

        ], [

            'current_password.required' =>
                'Current Password is required',

            'password.required' =>
                'New Password is required',

            'password.confirmed' =>
                'Password and Confirm Password does not match',

            'password.min' =>
                'Password must be at least 8 characters.',

            'password_confirmation.required' =>
                'Confirm Password is required',

            'password_confirmation.min' =>
                'Confirm Password must be at least 8 characters.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Match Old Password
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $request->current_password,
                Auth::user()->password
            )
        ) {

            return back()->with(
                'error',
                "Old Password Doesn't match!"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        User::whereId(
            Auth::user()->id
        )->update([

            'password' =>
                Hash::make(
                    $request->password
                ),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'message',
                'Password changed successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Admin Profile
    |--------------------------------------------------------------------------
    */

    public function adminProfile()
    {
        return view(
            'backend.auth.profile'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Admin Profile
    |--------------------------------------------------------------------------
    */

    public function updateAdminProfile(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' =>
                'required|string|max:255',

            'email' =>
                'required|string|email|max:255|unique:users,email,' .
                Auth::user()->id,

            'phone' =>
                'required|string|max:15',

            'profile_image' =>
                'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

        ], [

            'name.required' =>
                'Full Name is required',

            'email.required' =>
                'Email is required',

            'email.email' =>
                'Please enter a valid email address',

            'email.unique' =>
                'This email is already taken',

            'phone.required' =>
                'Phone number is required',

            'phone.max' =>
                'Phone number must not exceed 15 characters',

            'profile_image.image' =>
                'The profile image must be an image file',

            'profile_image.mimes' =>
                'Allowed formats: jpeg, png, jpg, webp',

            'profile_image.max' =>
                'File size cannot exceed 2MB',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Get User
        |--------------------------------------------------------------------------
        */

        $user = User::find(
            Auth::user()->id
        );


        /*
        |--------------------------------------------------------------------------
        | Basic Information
        |--------------------------------------------------------------------------
        */

        $user->name =
            $request->name;

        $user->email =
            $request->email;

        $user->phone =
            $request->phone;

        $user->updated_by =
            Auth::user()->id;

        $user->updated_at =
            now();


        /*
        |--------------------------------------------------------------------------
        | Profile Image Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Old Image
            |--------------------------------------------------------------------------
            */

            if ($user->profile_image) {

                $oldPath = public_path(
                    'backend/assets/uploads/profile/' .
                    $user->profile_image
                );

                if (file_exists($oldPath)) {

                    unlink($oldPath);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Image
            |--------------------------------------------------------------------------
            */

            $image =
                $request->file('profile_image');

            $extension =
                $image->getClientOriginalExtension();

            $newName =
                time() .
                rand(10, 999) .
                '.' .
                $extension;


            $image->move(

                public_path(
                    'backend/assets/uploads/profile'
                ),

                $newName

            );


            /*
            |--------------------------------------------------------------------------
            | Save Image Name
            |--------------------------------------------------------------------------
            */

            $user->profile_image =
                $newName;
        }


        /*
        |--------------------------------------------------------------------------
        | Save User
        |--------------------------------------------------------------------------
        */

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.profile')
            ->with(
                'message',
                'Profile updated successfully!'
            );
    }
}