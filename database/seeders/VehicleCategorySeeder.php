<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            [
                'name' => 'SUV',
                'code' => 'SUV',
                'description' => 'Sport Utility Vehicle category for SUVs such as Toyota Fortuner and similar vehicles.',
                'status' => 1,
            ],

            [
                'name' => 'MUV / MPV',
                'code' => 'MUV_MPV',
                'description' => 'Multi Utility Vehicle / Multi Purpose Vehicle category for vehicles such as Maruti Ertiga, Toyota Innova and Rumion.',
                'status' => 1,
            ],

            [
                'name' => 'Sedan',
                'code' => 'SEDAN',
                'description' => 'Sedan category for passenger cars such as Maruti Dzire.',
                'status' => 1,
            ],

            [
                'name' => 'Hatchback',
                'code' => 'HATCHBACK',
                'description' => 'Hatchback category for compact passenger cars such as Toyota Glanza.',
                'status' => 1,
            ],

            [
                'name' => 'Bus',
                'code' => 'BUS',
                'description' => 'Bus category for passenger and commercial buses such as Bharat Benz.',
                'status' => 1,
            ],

        ];


        foreach ($categories as $category) {

            VehicleCategory::updateOrCreate(
                [
                    'code' => $category['code'],
                ],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'status' => $category['status'],
                    'created_at' => now(),
                    'created_by' => 1,
                ]
            );
        }
    }
}