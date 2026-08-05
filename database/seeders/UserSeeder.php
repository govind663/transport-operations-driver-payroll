<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's default administrator.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'System Administrator',
            'phone'             => '9876543210',
            'profile_image'     => 'default-user.webp',
            'role'              => User::ROLE_ADMIN,
            'status'            => User::STATUS_ACTIVE,
            'email'             => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('Admin@123'),
            'created_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}