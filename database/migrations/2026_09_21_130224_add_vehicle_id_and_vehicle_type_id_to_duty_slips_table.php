<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('duty_slips', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | VEHICLE ID
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vehicle_id')
                ->nullable()
                ->after('driver_id')
                ->constrained('vehicle_management')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | VEHICLE TYPE ID
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vehicle_type_id')
                ->nullable()
                ->after('vehicle_id')
                ->constrained('vehicle_types')
                ->nullOnDelete();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('duty_slips', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | DROP VEHICLE TYPE FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'vehicle_type_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | DROP VEHICLE FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'vehicle_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | DROP COLUMNS
            |--------------------------------------------------------------------------
            */

            $table->dropColumn([
                'vehicle_type_id',
                'vehicle_id',
            ]);

        });
    }
};