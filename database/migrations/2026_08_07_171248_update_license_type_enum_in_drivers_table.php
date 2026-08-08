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
        DB::statement("
            ALTER TABLE drivers
            MODIFY license_type ENUM(
                'LMV',
                'HMV',
                'TRANSPORT',
                'LMV_TRANSPORT',
                'HMV_TRANSPORT',
                'MCWG',
                'MCWOG',
                'OTHER'
            )
            NULL
            DEFAULT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
            )
            NULL
            DEFAULT NULL
        ");
    }
};