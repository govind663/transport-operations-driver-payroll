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
        /*
        |--------------------------------------------------------------------------
        | Vehicle Categories
        |--------------------------------------------------------------------------
        */

        Schema::table('vehicle_categories', function (Blueprint $table) {

            $table->unsignedBigInteger('deleted_by')
                ->nullable()
                ->after('updated_by');

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

        });


        /*
        |--------------------------------------------------------------------------
        | Vehicle Types
        |--------------------------------------------------------------------------
        */

        Schema::table('vehicle_types', function (Blueprint $table) {

            $table->unsignedBigInteger('deleted_by')
                ->nullable()
                ->after('updated_by');

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Vehicle Categories
        |--------------------------------------------------------------------------
        */

        Schema::table('vehicle_categories', function (Blueprint $table) {

            $table->dropForeign([
                'deleted_by'
            ]);

            $table->dropColumn(
                'deleted_by'
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Vehicle Types
        |--------------------------------------------------------------------------
        */

        Schema::table('vehicle_types', function (Blueprint $table) {

            $table->dropForeign([
                'deleted_by'
            ]);

            $table->dropColumn(
                'deleted_by'
            );

        });
    }
};