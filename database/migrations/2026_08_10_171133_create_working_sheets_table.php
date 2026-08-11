<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_sheets', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Working Sheet Number
            |--------------------------------------------------------------------------
            */

            $table->string('sheet_no')->unique();

            /*
            |--------------------------------------------------------------------------
            | Duty Slip
            |--------------------------------------------------------------------------
            */

            $table->foreignId('duty_slip_id')
                ->constrained('duty_slips')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Work Details
            |--------------------------------------------------------------------------
            */

            $table->date('work_date');

            $table->decimal('opening_meter', 10, 2)
                ->nullable();

            $table->decimal('closing_meter', 10, 2)
                ->nullable();

            $table->decimal('total_km', 10, 2)
                ->nullable();

            $table->decimal('total_hours', 8, 2)
                ->nullable();

            $table->decimal('overtime_hours', 8, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Financial Details
            |--------------------------------------------------------------------------
            */

            $table->decimal('base_amount', 12, 2)
                ->default(0);

            $table->decimal('extra_km_amount', 12, 2)
                ->default(0);

            $table->decimal('overtime_amount', 12, 2)
                ->default(0);

            $table->decimal('other_amount', 12, 2)
                ->default(0);

            $table->decimal('total_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected',
                'completed',
            ])->default('draft');

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
        Schema::dropIfExists('working_sheets');
    }
};