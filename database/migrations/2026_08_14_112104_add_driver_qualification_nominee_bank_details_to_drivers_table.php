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
        Schema::table('drivers', function (Blueprint $table) {
            $table->json('driver_qualifications')->nullable()->after('pan_document');
            $table->json('driver_nominees')->nullable()->after('driver_qualifications');
            $table->json('driver_bank_details')->nullable()->after('driver_nominees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('driver_qualifications');
            $table->dropColumn('driver_nominees');
            $table->dropColumn('driver_bank_details');
        });
    }
};
