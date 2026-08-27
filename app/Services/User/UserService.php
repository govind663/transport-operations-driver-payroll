<?php

namespace App\Services\User;

use App\Mail\DriverWelcomeMail;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getUsers(): Collection
    {
        return User::latest()->get();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND USER
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): User {

        return User::findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): User {

        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | PROFILE IMAGE
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['profile_image']) &&
                $data['profile_image']
            ) {

                $data['profile_image'] =
                    $data['profile_image']
                        ->store('users', 'public');
            }


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            $data['password'] =
                Hash::make(
                    $data['password']
                );


            /*
            |--------------------------------------------------------------------------
            | CREATED BY
            |--------------------------------------------------------------------------
            */

            $data['created_by'] =
                Auth::id();


            /*
            |--------------------------------------------------------------------------
            | CREATE USER
            |--------------------------------------------------------------------------
            */

            return User::create($data);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE DRIVER CREDENTIAL
    |--------------------------------------------------------------------------
    |
    | Creates login credentials for Driver.
    |
    | Password:
    | Mastermind@XXXXXXXXXX
    |
    | Example:
    | Mastermind@K7x9Pq2Lm4
    |
    | Welcome email is sent from here.
    |
    |--------------------------------------------------------------------------
    */

    public function createDriverCredential(
        Driver $driver
    ): User {

        return DB::transaction(function () use ($driver) {

            /*
            |--------------------------------------------------------------------------
            | EXISTING DRIVER USER
            |--------------------------------------------------------------------------
            */

            if (
                !empty($driver->user_id)
            ) {

                $user = User::findOrFail(
                    $driver->user_id
                );


                /*
                |--------------------------------------------------------------------------
                | SYNC DRIVER PHOTO
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->driver_photo)
                ) {

                    $user->update([
                        'profile_image' =>
                            $driver->driver_photo,
                    ]);
                }


                return $user;
            }


            /*
            |--------------------------------------------------------------------------
            | FIND EXISTING USER
            |--------------------------------------------------------------------------
            */

            $user = null;


            /*
            |--------------------------------------------------------------------------
            | CHECK EMAIL
            |--------------------------------------------------------------------------
            */

            if (
                !empty($driver->email)
            ) {

                $user = User::query()
                    ->where(
                        'email',
                        $driver->email
                    )
                    ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | CHECK PHONE
            |--------------------------------------------------------------------------
            */

            if (
                !$user &&
                !empty($driver->mobile)
            ) {

                $user = User::query()
                    ->where(
                        'phone',
                        $driver->mobile
                    )
                    ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | DRIVER NAME
            |--------------------------------------------------------------------------
            */

            $name = trim(
                $driver->first_name .
                ' ' .
                ($driver->last_name ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | EXISTING USER FOUND
            |--------------------------------------------------------------------------
            */

            if ($user) {

                /*
                |--------------------------------------------------------------------------
                | SYNC DRIVER PROFILE IMAGE
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->driver_photo)
                ) {

                    $user->profile_image =
                        $driver->driver_photo;
                }


                $user->save();


                /*
                |--------------------------------------------------------------------------
                | UPDATE DRIVER USER ID
                |--------------------------------------------------------------------------
                */

                $driver->user_id =
                    $user->id;

                $driver->save();


                return $user;
            }


            /*
            |--------------------------------------------------------------------------
            | GENERATE STRONG TEMPORARY PASSWORD
            |--------------------------------------------------------------------------
            |
            | Format:
            |
            | Mastermind@XXXXXXXXXX
            |
            | Example:
            |
            | Mastermind@K7x9Pq2Lm4
            |
            |--------------------------------------------------------------------------
            */

            $temporaryPassword =
                $this->generateDriverPassword();


            /*
            |--------------------------------------------------------------------------
            | CREATE DRIVER USER
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                /*
                |--------------------------------------------------------------------------
                | BASIC INFORMATION
                |--------------------------------------------------------------------------
                */

                'name' =>
                    $name,

                'phone' =>
                    $driver->mobile,

                'email' =>
                    $driver->email,


                /*
                |--------------------------------------------------------------------------
                | PROFILE IMAGE
                |--------------------------------------------------------------------------
                */

                'profile_image' =>
                    $driver->driver_photo,


                /*
                |--------------------------------------------------------------------------
                | PASSWORD
                |--------------------------------------------------------------------------
                */

                'password' =>
                    Hash::make(
                        $temporaryPassword
                    ),


                /*
                |--------------------------------------------------------------------------
                | DRIVER ROLE
                |--------------------------------------------------------------------------
                */

                'role' =>
                    User::ROLE_DRIVER,


                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                'status' =>
                    User::STATUS_ACTIVE,


                /*
                |--------------------------------------------------------------------------
                | CREATED BY
                |--------------------------------------------------------------------------
                */

                'created_by' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | UPDATE DRIVER USER ID
            |--------------------------------------------------------------------------
            */

            $driver->user_id =
                $user->id;

            $driver->save();


            /*
            |--------------------------------------------------------------------------
            | SEND DRIVER WELCOME EMAIL
            |--------------------------------------------------------------------------
            |
            | Email contains:
            |
            | - Driver profile
            | - Driver basic information
            | - Driver role
            | - Login email
            | - Temporary password
            | - Mastermind Travels login URL
            |
            |--------------------------------------------------------------------------
            */

            if (
                !empty($driver->email)
            ) {

                Mail::to($driver->email)
                    ->cc('mastermindservices2009@gmail.com')
                    ->cc([
                        'mastermindservices2009@gmail.com',
                    ])
                    ->send(
                        new DriverWelcomeMail(
                            $driver->fresh([
                                'user',
                            ]),
                            $driver->email,
                            $temporaryPassword,
                            User::ROLE_DRIVER
                        )
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | RETURN USER
            |--------------------------------------------------------------------------
            */

            return $user;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE DRIVER PASSWORD
    |--------------------------------------------------------------------------
    |
    | Strong temporary password with Mastermind organization prefix.
    |
    | Example:
    | Mastermind@K7x9Pq2Lm4
    |
    |--------------------------------------------------------------------------
    */
    protected function generateDriverPassword(): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lowercase = 'abcdefghijkmnopqrstuvwxyz';
        $numbers = '23456789';
        $symbols = '@#$%&*!';

        $password =
            $uppercase[random_int(0, strlen($uppercase) - 1)] .
            $lowercase[random_int(0, strlen($lowercase) - 1)] .
            $numbers[random_int(0, strlen($numbers) - 1)] .
            $symbols[random_int(0, strlen($symbols) - 1)];

        $characters =
            $uppercase .
            $lowercase .
            $numbers .
            $symbols;

        for ($i = 0; $i < 8; $i++) {
            $password .=
                $characters[
                    random_int(
                        0,
                        strlen($characters) - 1
                    )
                ];
        }

        return 'Mastermind@' . str_shuffle($password);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        array $data
    ): User {

        return DB::transaction(
            function () use ($user, $data) {

                /*
                |--------------------------------------------------------------------------
                | PROFILE IMAGE
                |--------------------------------------------------------------------------
                */

                if (
                    isset($data['profile_image']) &&
                    $data['profile_image']
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | DELETE OLD IMAGE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $user->profile_image &&
                        Storage::disk('public')
                            ->exists(
                                $user->profile_image
                            )
                    ) {

                        Storage::disk('public')
                            ->delete(
                                $user->profile_image
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | STORE NEW IMAGE
                    |--------------------------------------------------------------------------
                    */

                    $data['profile_image'] =
                        $data['profile_image']
                            ->store(
                                'users',
                                'public'
                            );
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATED BY
                |--------------------------------------------------------------------------
                */

                $data['updated_by'] =
                    Auth::id();


                /*
                |--------------------------------------------------------------------------
                | UPDATE USER
                |--------------------------------------------------------------------------
                */

                $user->update($data);


                /*
                |--------------------------------------------------------------------------
                | RETURN FRESH USER
                |--------------------------------------------------------------------------
                */

                return $user->refresh();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function delete(
        User $user
    ): bool {

        $user->update([
            'deleted_by' => Auth::id(),
        ]);

        return $user->delete();
    }
}