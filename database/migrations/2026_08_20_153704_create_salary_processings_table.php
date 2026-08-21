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
        Schema::create('salary_processings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Driver Information
            |--------------------------------------------------------------------------
            |
            | Salary is processed against Driver.
            |
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
            | Example:
            | driver
            |
            */

            $table->string('role', 50)
                ->default('driver')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Salary Period
            |--------------------------------------------------------------------------
            |
            | Example:
            | salary_month = 08
            | salary_year  = 2026
            |
            */

            $table->unsignedTinyInteger('salary_month');

            $table->unsignedSmallInteger('salary_year');


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
            | Salary Details
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
            | Deduction Details
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
            | Total Deduction
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
            | Processing Status
            |--------------------------------------------------------------------------
            |
            | draft
            | processed
            | approved
            | paid
            | cancelled
            |
            */

            $table->string('status', 30)
                ->default('draft')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            $table->date('payment_date')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('paid_by')
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


            /*
            |--------------------------------------------------------------------------
            | Unique Salary Period
            |--------------------------------------------------------------------------
            |
            | One salary processing record per driver
            | for one month/year.
            |
            */

            $table->unique(
                [
                    'driver_id',
                    'salary_month',
                    'salary_year',
                ],
                'salary_driver_period_unique'
            );

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_processings');
    }
};