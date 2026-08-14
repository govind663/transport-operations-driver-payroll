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
        Schema::create('allowances', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */
            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Allowance Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('allowance_code', 100)
                ->unique();

            $table->string('allowance_name', 150);

            $table->text('description')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Calculation Information
            |--------------------------------------------------------------------------
            |
            | fixed      = Fixed amount
            | percentage = Percentage based
            |
            */

            $table->enum('calculation_type', [
                'fixed',
                'percentage',
            ])->default('fixed');


            /*
            |--------------------------------------------------------------------------
            | Amount / Percentage
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 15, 2)
                ->nullable();

            $table->decimal('percentage', 8, 2)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Tax Information
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_taxable')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')
                ->default(true);


            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Soft Deletes
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('allowance_name');
            $table->index('calculation_type');
            $table->index('status');
            $table->index('is_taxable');
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