<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Normalize Existing Licence Type Values
        |--------------------------------------------------------------------------
        |
        | Existing values ko ENUM mein convert karne se pehle
        | normalize kar rahe hain.
        |
        */

        DB::table('drivers')
            ->whereNotNull('license_type')
            ->update([
                'license_type' => DB::raw('UPPER(TRIM(license_type))'),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Convert license_type To ENUM
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE drivers
            MODIFY license_type ENUM(
                'LMV',
                'LMV-NT',
                'LMV-TR',
                'HMV',
                'HGV',
                'HPV',
                'TRANSPORT',
                'MCWG',
                'MCWOG'
            ) NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Convert ENUM Back To VARCHAR
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE drivers
            MODIFY license_type VARCHAR(50) NULL
        ");
    }
};