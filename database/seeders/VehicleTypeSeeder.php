<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [

            /*
            |--------------------------------------------------------------------------
            | SUV
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 1,
                'name' => 'FORTUNER',
                'code' => 'FORTUNER',
                'description' => 'Toyota Fortuner SUV.',
                'status' => 1,
            ],

            [
                'vehicle_category_id' => 1,
                'name' => 'KIA',
                'code' => 'KIA',
                'description' => 'Kia vehicle type. Exact model can be updated later.',
                'status' => 1,
            ],


            /*
            |--------------------------------------------------------------------------
            | MUV / MPV
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 2,
                'name' => 'MARUTI ERTIGA',
                'code' => 'MARUTI_ERTIGA',
                'description' => 'Maruti Ertiga MUV / MPV.',
                'status' => 1,
            ],

            [
                'vehicle_category_id' => 2,
                'name' => 'INNOVA HYCROSS',
                'code' => 'INNOVA_HYCROSS',
                'description' => 'Toyota Innova Hycross MUV / MPV.',
                'status' => 1,
            ],

            [
                'vehicle_category_id' => 2,
                'name' => 'INNOVA CRYSTA',
                'code' => 'INNOVA_CRYSTA',
                'description' => 'Toyota Innova Crysta MUV / MPV.',
                'status' => 1,
            ],

            [
                'vehicle_category_id' => 2,
                'name' => 'RUMION S CNG MANUAL',
                'code' => 'RUMION_S_CNG_MANUAL',
                'description' => 'Toyota Rumion S CNG Manual MUV / MPV.',
                'status' => 1,
            ],


            /*
            |--------------------------------------------------------------------------
            | SEDAN
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 3,
                'name' => 'MARUTI DZIRE',
                'code' => 'MARUTI_DZIRE',
                'description' => 'Maruti Dzire Sedan.',
                'status' => 1,
            ],


            /*
            |--------------------------------------------------------------------------
            | HATCHBACK
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 4,
                'name' => 'TOYOTA GLANZA',
                'code' => 'TOYOTA_GLANZA',
                'description' => 'Toyota Glanza Hatchback.',
                'status' => 1,
            ],


            /*
            |--------------------------------------------------------------------------
            | BUS
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 5,
                'name' => 'BHARAT BENZ (BUS)',
                'code' => 'BHARAT_BENZ_BUS',
                'description' => 'Bharat Benz passenger / commercial bus.',
                'status' => 1,
            ],

        ];


        foreach ($vehicleTypes as $vehicleType) {

            VehicleType::updateOrCreate(
                [
                    'code' => $vehicleType['code'],
                ],
                [
                    'vehicle_category_id' => $vehicleType['vehicle_category_id'],
                    'name' => $vehicleType['name'],
                    'description' => $vehicleType['description'],
                    'status' => $vehicleType['status'],
                    'created_at' => now(),
                    'created_by' => 1,
                ]
            );
        }
    }
}