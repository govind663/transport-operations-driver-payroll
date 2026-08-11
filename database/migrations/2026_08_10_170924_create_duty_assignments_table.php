<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duty_assignments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Assignment Number
            |--------------------------------------------------------------------------
            */

            $table->string('assignment_no')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Travel Request
            |--------------------------------------------------------------------------
            */

            $table->foreignId('travel_request_id')
                ->constrained('travel_requests')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            |
            | VehicleManagement model
            | Table: vehicle_management
            | FK: vehicle_management.id
            |
            */

            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicle_management')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Assignment Details
            |--------------------------------------------------------------------------
            */

            $table->dateTime('assigned_at')
                ->nullable();

            $table->dateTime('reporting_time')
                ->nullable();

            $table->string('reporting_location')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'assigned',
                'accepted',
                'rejected',
                'started',
                'completed',
                'cancelled',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

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
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('duty_assignments');
    }
};