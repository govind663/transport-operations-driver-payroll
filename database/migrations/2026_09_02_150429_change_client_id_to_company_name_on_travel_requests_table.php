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
        Schema::table('travel_requests', function (Blueprint $table) {

            // Remove client_id foreign key
            $table->dropForeign(['client_id']);

            // Remove client_id column
            $table->dropColumn('client_id');

            // Add company_name
            $table->string('company_name')
                ->nullable()
                ->after('request_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {

            // Remove company_name
            $table->dropColumn('company_name');

            // Restore client_id
            $table->foreignId('client_id')
                ->nullable()
                ->after('request_no')
                ->constrained('clients')
                ->nullOnDelete();
        });
    }
};