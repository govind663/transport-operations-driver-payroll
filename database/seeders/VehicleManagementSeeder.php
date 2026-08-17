<?php

namespace Database\Seeders;

use App\Models\VehicleManagement;
use Illuminate\Database\Seeder;

class VehicleManagementSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Vehicle Master Data
        |--------------------------------------------------------------------------
        |
        | Vehicle Category IDs:
        |
        | 1 = SUV
        | 2 = MUV / MPV
        | 3 = Sedan
        | 4 = Hatchback
        | 5 = Bus
        |
        | Vehicle Type IDs:
        |
        | 1 = FORTUNER
        | 2 = KIA
        | 3 = MARUTI ERTIGA
        | 4 = INNOVA HYCROSS
        | 5 = INNOVA CRYSTA
        | 6 = RUMION S CNG MANUAL
        | 7 = MARUTI DZIRE
        | 8 = TOYOTA GLANZA
        | 9 = BHARAT BENZ (BUS)
        |
        |--------------------------------------------------------------------------
        */

        $vehicles = [

            /*
            |--------------------------------------------------------------------------
            | FORTUNER
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 1,
                'vehicle_type_id'     => 1,
                'vehicle_number'      => 'MH 43 CL 2525',
                'manufacturer'        => 'Toyota',
                'model'               => 'Fortuner',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | MARUTI ERTIGA
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CK 1184',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CK 1185',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CK 1186',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CK 1187',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CK 1189',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 BX 5078',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 BX 5849',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CE 7125',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CE 7126',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH 43 CE 7921',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 3,
                'vehicle_number'      => 'MH43CE3320',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Ertiga',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | INNOVA HYCROSS
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 4,
                'vehicle_number'      => 'MH 43 CE 4017',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Hycross',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | INNOVA CRYSTA
            |--------------------------------------------------------------------------
            */

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 7949',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 5674',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 5675',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 8422',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 8781',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CE 7124',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CK 5170',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 43 CK 5174',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 5,
                'vehicle_number'      => 'MH 46 CL 8327',
                'manufacturer'        => 'Toyota',
                'model'               => 'Innova Crysta',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | MARUTI DZIRE
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CK 9161',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CK 9162',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CK 9163',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CK9403',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CK9398',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 5891',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 5892',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 5890',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 5889',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 5887',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 2812',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 4889',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 4890',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 9030',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 9031',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 BX 9032',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CE 0479',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CE 0480',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CE 0481',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CE 0482',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 3,
                'vehicle_type_id'     => 7,
                'vehicle_number'      => 'MH 43 CE 0483',
                'manufacturer'        => 'Maruti Suzuki',
                'model'               => 'Dzire',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | TOYOTA GLANZA
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 4,
                'vehicle_type_id'     => 8,
                'vehicle_number'      => 'MH 43 BX 8185',
                'manufacturer'        => 'Toyota',
                'model'               => 'Glanza',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 4,
                'vehicle_type_id'     => 8,
                'vehicle_number'      => 'MH 43 BX 8186',
                'manufacturer'        => 'Toyota',
                'model'               => 'Glanza',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | BHARAT BENZ BUS
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 5,
                'vehicle_type_id'     => 9,
                'vehicle_number'      => 'MH 43 CE 0376',
                'manufacturer'        => 'Bharat Benz',
                'model'               => 'Bus',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | KIA
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 1,
                'vehicle_type_id'     => 2,
                'vehicle_number'      => 'MH 43 CE 8938',
                'manufacturer'        => 'KIA',
                'model'               => 'KIA',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 1,
                'vehicle_type_id'     => 2,
                'vehicle_number'      => 'MH 43 CE 8671',
                'manufacturer'        => 'KIA',
                'model'               => 'KIA',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 1,
                'vehicle_type_id'     => 2,
                'vehicle_number'      => 'MH 43 CE 8673',
                'manufacturer'        => 'KIA',
                'model'               => 'KIA',
                'status'              => 'active',
            ],


            /*
            |--------------------------------------------------------------------------
            | RUMION
            |--------------------------------------------------------------------------
            */
            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 6,
                'vehicle_number'      => 'MH 43 CQ 9583',
                'manufacturer'        => 'Toyota',
                'model'               => 'Rumion S CNG Manual',
                'status'              => 'active',
            ],

            [
                'vehicle_category_id' => 2,
                'vehicle_type_id'     => 6,
                'vehicle_number'      => 'MH 43 CQ 9584',
                'manufacturer'        => 'Toyota',
                'model'               => 'Rumion S CNG Manual',
                'status'              => 'active',
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Insert / Update Vehicle Records
        |--------------------------------------------------------------------------
        */
        foreach ($vehicles as $vehicle) {

            VehicleManagement::updateOrCreate(

                /*
                |--------------------------------------------------------------------------
                | Unique Vehicle Number
                |--------------------------------------------------------------------------
                */
                [
                    'vehicle_number' => $vehicle['vehicle_number'],
                ],

                /*
                |--------------------------------------------------------------------------
                | Vehicle Data
                |--------------------------------------------------------------------------
                */
                [
                    'vehicle_category_id' => $vehicle['vehicle_category_id'],
                    'vehicle_type_id' => $vehicle['vehicle_type_id'],
                    'manufacturer' => $vehicle['manufacturer'],
                    'model' => $vehicle['model'],
                    'status' => $vehicle['status'],
                    'created_at' => now(),
                    'created_by' => '1',                                                            
                ]
            );
        }
    }
}