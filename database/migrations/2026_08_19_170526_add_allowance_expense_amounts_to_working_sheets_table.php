<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('working_sheets', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Duty Slip Financial Details
            |--------------------------------------------------------------------------
            */

            $table->decimal('allowance_amount', 12, 2)
                ->default(0)
                ->after('overtime_amount');

            $table->decimal('expense_amount', 12, 2)
                ->default(0)
                ->after('allowance_amount');

        });
    }

    public function down(): void
    {
        Schema::table('working_sheets', function (Blueprint $table) {

            $table->dropColumn([
                'allowance_amount',
                'expense_amount',
            ]);

        });
    }
};