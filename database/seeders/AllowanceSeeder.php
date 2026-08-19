<?php

namespace Database\Seeders;

use App\Models\Allowance;
use Illuminate\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowances = [

            /*
            |--------------------------------------------------------------------------
            | 01. Day Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW001',
                'name' => 'Day Allowance',
                'description' => 'Daily allowance provided to transport staff for duty-related expenses.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 02. Night Halt Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW002',
                'name' => 'Night Halt Allowance',
                'description' => 'Allowance provided when the driver or transport staff has to halt overnight during duty.',
                'amount' => 700.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 03. Outstation Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW003',
                'name' => 'Outstation Allowance',
                'description' => 'Allowance provided to transport staff for outstation duties and trips.',
                'amount' => 800.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 04. Driver Trip Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW004',
                'name' => 'Driver Trip Allowance',
                'description' => 'Allowance provided to drivers for completing assigned transport trips.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 05. Cleaner Trip Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW005',
                'name' => 'Cleaner Trip Allowance',
                'description' => 'Allowance provided to cleaners or helpers accompanying transport trips.',
                'amount' => 300.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 06. Food / Meal Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW006',
                'name' => 'Food / Meal Allowance',
                'description' => 'Allowance provided for food and meal expenses during transport duty.',
                'amount' => 300.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 07. KM Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW007',
                'name' => 'KM Allowance',
                'description' => 'Allowance calculated based on kilometres travelled during assigned duty.',
                'amount' => 5.00,
                'calculation_type' => Allowance::CALCULATION_PER_KM,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 08. Overtime Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW008',
                'name' => 'Overtime Allowance',
                'description' => 'Allowance provided for working beyond scheduled duty hours.',
                'amount' => 100.00,
                'calculation_type' => Allowance::CALCULATION_PER_HOUR,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 09. Waiting Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW009',
                'name' => 'Waiting Allowance',
                'description' => 'Allowance provided to drivers for waiting time during transport operations.',
                'amount' => 75.00,
                'calculation_type' => Allowance::CALCULATION_PER_HOUR,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. Loading / Unloading Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW010',
                'name' => 'Loading / Unloading Allowance',
                'description' => 'Allowance provided for duties related to loading and unloading cargo or goods.',
                'amount' => 300.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 11. Detention Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW011',
                'name' => 'Detention Allowance',
                'description' => 'Allowance provided when a vehicle and driver are detained beyond the expected operational time.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 12. Night Shift Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW012',
                'name' => 'Night Shift Allowance',
                'description' => 'Allowance provided to transport staff assigned to night shift duties.',
                'amount' => 300.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 13. Holiday Duty Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW013',
                'name' => 'Holiday Duty Allowance',
                'description' => 'Allowance provided for performing transport duties on public or declared holidays.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 14. Weekly Off Duty Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW014',
                'name' => 'Weekly Off Duty Allowance',
                'description' => 'Allowance provided when transport staff performs duty on their scheduled weekly off.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_PER_DAY,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 15. Emergency Duty Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW015',
                'name' => 'Emergency Duty Allowance',
                'description' => 'Allowance provided for emergency transport duties outside normal assignments.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 16. Long Route Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW016',
                'name' => 'Long Route Allowance',
                'description' => 'Allowance provided for completing long-distance transport routes.',
                'amount' => 750.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 17. Safe Driving Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW017',
                'name' => 'Safe Driving Allowance',
                'description' => 'Performance-based allowance for maintaining safe driving practices and avoiding preventable incidents.',
                'amount' => 1000.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 18. Fuel Efficiency Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW018',
                'name' => 'Fuel Efficiency Allowance',
                'description' => 'Performance-based allowance for achieving predefined vehicle fuel efficiency targets.',
                'amount' => 1000.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 19. Trip Completion Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW019',
                'name' => 'Trip Completion Allowance',
                'description' => 'Allowance provided for successfully completing assigned transport trips.',
                'amount' => 300.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

            /*
            |--------------------------------------------------------------------------
            | 20. On-Time Delivery Allowance
            |--------------------------------------------------------------------------
            */

            [
                'allowance_code' => 'ALW020',
                'name' => 'On-Time Delivery Allowance',
                'description' => 'Performance-based allowance for completing deliveries within the scheduled delivery time.',
                'amount' => 500.00,
                'calculation_type' => Allowance::CALCULATION_FIXED,
                'status' => Allowance::STATUS_ACTIVE,
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Insert / Update Allowances
        |--------------------------------------------------------------------------
        */

        foreach ($allowances as $allowance) {

            Allowance::updateOrCreate(
                [
                    'allowance_code' => $allowance['allowance_code'],
                ],
                $allowance
            );
        }
    }
}