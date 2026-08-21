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
        Schema::create('salary_slips', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Salary Processing Reference
            |--------------------------------------------------------------------------
            |
            | Each salary slip belongs to one processed salary.
            |
            */

            $table->foreignId('salary_processing_id')
                ->constrained('salary_processings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Driver Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            |
            | Historical/reference value.
            | Driver salary slip will normally have "driver".
            |
            */

            $table->string('role', 50)
                ->default('driver')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Salary Slip Number
            |--------------------------------------------------------------------------
            |
            | Example:
            | SAL-2026-00001
            |
            */

            $table->string('slip_no')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Salary Period
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('salary_month');

            $table->unsignedSmallInteger('salary_year');


            /*
            |--------------------------------------------------------------------------
            | Salary Period Dates
            |--------------------------------------------------------------------------
            */

            $table->date('period_from')
                ->nullable();

            $table->date('period_to')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Attendance / Working Details
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_working_days', 8, 2)
                ->default(0);

            $table->decimal('present_days', 8, 2)
                ->default(0);

            $table->decimal('absent_days', 8, 2)
                ->default(0);

            $table->decimal('paid_days', 8, 2)
                ->default(0);

            $table->decimal('overtime_hours', 10, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Earnings
            |--------------------------------------------------------------------------
            */

            $table->decimal('basic_salary', 12, 2)
                ->default(0);

            $table->decimal('allowance_amount', 12, 2)
                ->default(0);

            $table->decimal('overtime_amount', 12, 2)
                ->default(0);

            $table->decimal('bonus_amount', 12, 2)
                ->default(0);

            $table->decimal('other_earnings', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Gross Salary
            |--------------------------------------------------------------------------
            */

            $table->decimal('gross_salary', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Deductions
            |--------------------------------------------------------------------------
            */

            $table->decimal('advance_deduction', 12, 2)
                ->default(0);

            $table->decimal('loan_deduction', 12, 2)
                ->default(0);

            $table->decimal('penalty_deduction', 12, 2)
                ->default(0);

            $table->decimal('other_deductions', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Total Deductions
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_deductions', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Net Salary
            |--------------------------------------------------------------------------
            */

            $table->decimal('net_salary', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            $table->date('payment_date')
                ->nullable();

            $table->string('payment_status', 30)
                ->default('unpaid')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Slip Status
            |--------------------------------------------------------------------------
            |
            | generated
            | issued
            | cancelled
            |
            */

            $table->string('status', 30)
                ->default('generated')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Notes / Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Generated Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Issued Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('issued_at')
                ->nullable();


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
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index(
                [
                    'driver_id',
                    'salary_month',
                    'salary_year',
                ],
                'salary_slips_driver_period_index'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};