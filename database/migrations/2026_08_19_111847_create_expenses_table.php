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
        Schema::create('expenses', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Expense Information
            |--------------------------------------------------------------------------
            */

            $table->string('expense_code', 100)
                ->unique();

            $table->string('name', 150);

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Expense Category
            |--------------------------------------------------------------------------
            */

            $table->enum('expense_type', [
                'fuel',
                'toll',
                'parking',
                'food',
                'maintenance',
                'repair',
                'miscellaneous',
            ])->default('miscellaneous');

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2)
                ->default(0);

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
            $table->index('expense_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};