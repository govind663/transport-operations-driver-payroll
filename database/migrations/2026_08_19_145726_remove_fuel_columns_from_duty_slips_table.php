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
            | Remove Old Fuel Columns
            |--------------------------------------------------------------------------
            */
            $table->dropColumn([
                'fuel_quantity',
                'fuel_amount',
            ]);

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
            | Restore Old Fuel Columns
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'fuel_quantity',
                12,
                2
            )->nullable();

            $table->decimal(
                'fuel_amount',
                12,
                2
            )->nullable();
        });
    }
};
