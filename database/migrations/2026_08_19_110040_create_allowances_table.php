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
        // Remove existing table first
        Schema::dropIfExists('allowances');

        // Create fresh allowances table
        Schema::create('allowances', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Allowance Information
            |--------------------------------------------------------------------------
            */

            $table->string('allowance_code', 100)
                ->unique();

            $table->string('name', 150);

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amount / Calculation
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2)
                ->default(0);

            $table->enum('calculation_type', [
                'fixed',
                'per_day',
                'per_km',
                'per_hour',
            ])->default('fixed');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->unsignedBigInteger('updated_by')
                ->nullable();

            $table->unsignedBigInteger('deleted_by')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('name');
            $table->index('status');
            $table->index('calculation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowances');
    }
};