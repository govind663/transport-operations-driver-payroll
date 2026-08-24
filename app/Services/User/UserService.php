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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
    
        if (!empty($driver->user_id)) {
    
            $user = User::findOrFail(
                $driver->user_id
            );
    
            if (!empty($driver->driver_photo)) {
    
                $user->update([
                    'profile_image' => $driver->driver_photo,
                    'updated_by'    => Auth::id(),
                ]);
            }
    
            return $user;
        }
    
    
        $user = null;
    
    
        if (!empty($driver->email)) {
    
            $user = User::query()
                ->where('email', $driver->email)
                ->first();
        }
    
    
        if (
            !$user &&
            !empty($driver->mobile)
        ) {
    
            $user = User::query()
                ->where('phone', $driver->mobile)
                ->first();
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | EXISTING USER
        |--------------------------------------------------------------------------
        */
    
        if ($user) {
    
            if (!empty($driver->driver_photo)) {
    
                $user->profile_image =
                    $driver->driver_photo;
            }
    
            $user->updated_by =
                Auth::id();
    
            $user->save();
    
    
            $driver->user_id =
                $user->id;
    
            $driver->save();
    
    
            return $user;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | CREATE NEW USER
        |--------------------------------------------------------------------------
        */
    
        $temporaryPassword =
            $this->generateDriverPassword();
    
    
        $name = trim(
            $driver->first_name .
            ' ' .
            ($driver->last_name ?? '')
        );
    
    
        $user = User::create([
    
            'name' =>
                $name,
    
            'phone' =>
                $driver->mobile,
    
            'email' =>
                $driver->email,
    
            'profile_image' =>
                $driver->driver_photo,
    
            'password' =>
                Hash::make(
                    $temporaryPassword
                ),
    
            'role' =>
                User::ROLE_DRIVER,
    
            'status' =>
                User::STATUS_ACTIVE,
    
            'created_by' =>
                Auth::id(),
    
        ]);
    
    
        /*
        |--------------------------------------------------------------------------
        | LINK DRIVER
        |--------------------------------------------------------------------------
        */
    
        $driver->user_id =
            $user->id;
    
        $driver->save();
    
    
        /*
        |--------------------------------------------------------------------------
        | STORE TEMPORARY PASSWORD FOR EMAIL
        |--------------------------------------------------------------------------
        */
    
        $this->sendDriverWelcomeEmail(
            $driver,
            $user,
            $temporaryPassword
        );
    
    
        return $user;
    }
    
    protected function sendDriverWelcomeEmail(
        Driver $driver,
        User $user,
        string $temporaryPassword
    ): void {
    
        if (empty($driver->email)) {
            return;
        }
    
        try {
    
            Mail::to($driver->email)->send(
                new DriverWelcomeMail(
                    $driver->fresh([
                        'user',
                    ]),
                    $driver->email,
                    $temporaryPassword,
                    User::ROLE_DRIVER
                )
            );
    
        } catch (\Throwable $e) {
    
            Log::error(
                'Driver welcome email failed.',
                [
                    'driver_id' =>
                        $driver->id,
    
                    'user_id' =>
                        $user->id,
    
                    'email' =>
                        $driver->email,
    
                    'exception' =>
                        $e,
                ]
            );
        }
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