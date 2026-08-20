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
        Schema::create('driver_attendances', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */
            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */
            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Attendance Date
            |--------------------------------------------------------------------------
            */
            $table->date('attendance_date');


            $table->enum('status', [

                // Basic Attendance
                'present',
                'absent',
                'half_day',

                // Attendance Timing
                'late',
                'early_exit',
                'present_late',
                'present_early_exit',

                // Leave / Holiday
                'leave',
                'holiday',
                'weekly_off',
                'restricted_holiday',

                // Duty / Work Location
                'on_duty',
                'work_from_home',

                // Leave Types
                'comp_off',
                'maternity_leave',
                'sick_leave',
                'casual_leave',
                'paid_leave',
                'unpaid_leave',

                // Attendance Exceptions
                'missing_punch',
                'pending',

            ])->default('present');


            /*
            |--------------------------------------------------------------------------
            | Punch / Working Time
            |--------------------------------------------------------------------------
            */
            $table->time('in_time')
                ->nullable();

            $table->time('out_time')
                ->nullable();

            $table->decimal('total_hours', 5, 2)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Working Sheet Reference
            |--------------------------------------------------------------------------
            |
            | Multiple working sheets can belong to one driver/date.
            | Therefore this column is NOT unique.
            |
            */
            $table->unsignedBigInteger('working_sheet_id')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Attendance Source
            |--------------------------------------------------------------------------
            */
            $table->enum('source', [

                'working_sheet',
                'manual',
                'system',
                'import',

            ])->default('system');


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
            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->unsignedBigInteger('updated_by')
                ->nullable();


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


            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */
            $table->foreign('working_sheet_id')
                ->references('id')
                ->on('working_sheets')
                ->nullOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Unique Attendance
            |--------------------------------------------------------------------------
            |
            | One attendance record per driver per date.
            |
            */
            $table->unique(
                ['driver_id', 'attendance_date'],
                'driver_attendance_unique'
            );


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index(
                ['attendance_date', 'status'],
                'driver_attendance_date_status_index'
            );

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_attendances');
    }
};