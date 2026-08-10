<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_management', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vehicle_category_id')
                ->constrained('vehicle_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TYPE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vehicle_type_id')
                ->constrained('vehicle_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | VEHICLE INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('vehicle_number', 30)
                ->unique();

            $table->string('registration_number', 50)
                ->nullable();

            $table->string('chassis_number', 100)
                ->nullable()
                ->unique();

            $table->string('engine_number', 100)
                ->nullable()
                ->unique();

            $table->string('manufacturer', 100)
                ->nullable();

            $table->string('model', 100)
                ->nullable();

            $table->year('manufacturing_year')
                ->nullable();

            $table->string('color', 50)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | CAPACITY
            |--------------------------------------------------------------------------
            */

            $table->decimal('capacity', 10, 2)
                ->nullable();

            $table->string('capacity_unit', 20)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'active',
                'inactive',
                'maintenance',
                'sold'
            ])->default('active');

            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | AUDIT
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

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_management');
    }
};