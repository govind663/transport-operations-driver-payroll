<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    public function findById(string|int $id): User
    {
        return User::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {

            if (isset($data['profile_image'])) {

                $data['profile_image'] = $data['profile_image']
                    ->store('users', 'public');
            }

            $data['password'] = Hash::make($data['password']);

            $data['created_by'] = Auth::id();

            return User::create($data);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (isset($data['profile_image'])) {

                // Delete old image (optional)

                if (
                    $user->profile_image &&
                    Storage::disk('public')->exists($user->profile_image)
                ) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $data['profile_image'] = $data['profile_image']
                    ->store('users', 'public');
            }

            $data['updated_by'] = Auth::id();

            $user->update($data);

            return $user->refresh();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function delete(User $user): bool
    {
        $user->update([
            'deleted_by' => Auth::id(),
        ]);

        return $user->delete();
    }
}