<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        | Prevent Duplicate Salary Slips Before Adding Unique Constraint
        |--------------------------------------------------------------------------
        |
        | One Salary Processing record should generate only one Salary Slip.
        | Do not delete existing data automatically if duplicates already exist.
        |
        */

        $hasDuplicateSalarySlips = DB::table('salary_slips')
            ->select('salary_processing_id')
            ->groupBy('salary_processing_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicateSalarySlips) {
            throw new RuntimeException(
                'Cannot add the unique Salary Slip constraint: duplicate salary_processing_id values already exist in salary_slips. Resolve those duplicates first, then run php artisan migrate again.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Salary Processing Table
        |--------------------------------------------------------------------------
        */

        Schema::table('salary_processings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Working Sheet Summary
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_duty_days', 8, 2)
                ->default(0)
                ->after('overtime_hours');

            $table->decimal('total_km', 12, 2)
                ->default(0)
                ->after('total_duty_days');

            /*
            |--------------------------------------------------------------------------
            | Working Sheet Financial Snapshot
            |--------------------------------------------------------------------------
            |
            | allowance_amount and overtime_amount already exist in the table.
            | These new columns preserve the additional Working Sheet components.
            |
            */

            $table->decimal('working_sheet_base_amount', 12, 2)
                ->default(0)
                ->after('overtime_amount');

            $table->decimal('extra_km_amount', 12, 2)
                ->default(0)
                ->after('working_sheet_base_amount');

            $table->decimal('expense_reimbursement_amount', 12, 2)
                ->default(0)
                ->after('extra_km_amount');

            $table->decimal('working_sheet_other_amount', 12, 2)
                ->default(0)
                ->after('expense_reimbursement_amount');

            $table->decimal('working_sheet_total_amount', 12, 2)
                ->default(0)
                ->after('working_sheet_other_amount');

            /*
            |--------------------------------------------------------------------------
            | Audit Columns
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->after('paid_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->after('created_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->after('updated_by')
                ->constrained('users')
                ->nullOnDelete();

            // deleted_at already exists in the approved table.
        });

        /*
        |--------------------------------------------------------------------------
        | Update Salary Slips Table
        |--------------------------------------------------------------------------
        |
        | These columns store a snapshot of the amounts approved in
        | Salary Processing. Salary Slip generation will copy these values;
        | it should not calculate the salary a second time.
        |
        */

        Schema::table('salary_slips', function (Blueprint $table) {

            $table->decimal('total_duty_days', 8, 2)
                ->default(0)
                ->after('overtime_hours');

            $table->decimal('total_km', 12, 2)
                ->default(0)
                ->after('total_duty_days');

            $table->decimal('working_sheet_base_amount', 12, 2)
                ->default(0)
                ->after('overtime_amount');

            $table->decimal('extra_km_amount', 12, 2)
                ->default(0)
                ->after('working_sheet_base_amount');

            $table->decimal('expense_reimbursement_amount', 12, 2)
                ->default(0)
                ->after('extra_km_amount');

            $table->decimal('working_sheet_other_amount', 12, 2)
                ->default(0)
                ->after('expense_reimbursement_amount');

            $table->decimal('working_sheet_total_amount', 12, 2)
                ->default(0)
                ->after('working_sheet_other_amount');

            /*
            |--------------------------------------------------------------------------
            | One Salary Slip Per Salary Processing
            |--------------------------------------------------------------------------
            */

            $table->unique(
                'salary_processing_id',
                'salary_slips_processing_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roll Back Salary Slips Table
        |--------------------------------------------------------------------------
        */

        Schema::table('salary_slips', function (Blueprint $table) {

            $table->dropUnique('salary_slips_processing_unique');

            $table->dropColumn([
                'total_duty_days',
                'total_km',
                'working_sheet_base_amount',
                'extra_km_amount',
                'expense_reimbursement_amount',
                'working_sheet_other_amount',
                'working_sheet_total_amount',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Roll Back Salary Processing Table
        |--------------------------------------------------------------------------
        */

        Schema::table('salary_processings', function (Blueprint $table) {

            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            $table->dropColumn([
                'total_duty_days',
                'total_km',
                'working_sheet_base_amount',
                'extra_km_amount',
                'expense_reimbursement_amount',
                'working_sheet_other_amount',
                'working_sheet_total_amount',
                'created_by',
                'updated_by',
                'deleted_by',
            ]);
        });
    }
};
