<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duty_slips', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Duty Slip Number
            |--------------------------------------------------------------------------
            */

            $table->string('slip_no')->unique();

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            $table->foreignId('duty_assignment_id')
                ->constrained('duty_assignments')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Duty Information
            |--------------------------------------------------------------------------
            */

            $table->date('duty_date');

            $table->dateTime('start_time')
                ->nullable();

            $table->dateTime('end_time')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Meter Reading
            |--------------------------------------------------------------------------
            */

            $table->decimal('opening_meter', 10, 2)
                ->nullable();

            $table->decimal('closing_meter', 10, 2)
                ->nullable();

            $table->decimal('total_km', 10, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Fuel
            |--------------------------------------------------------------------------
            */

            $table->decimal('fuel_quantity', 10, 2)
                ->nullable();

            $table->decimal('fuel_amount', 12, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Duty Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'open',
                'started',
                'completed',
                'cancelled',
            ])->default('open');

            $table->text('remarks')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
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
        Schema::dropIfExists('duty_slips');
    }
};