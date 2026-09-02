<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {

            // Remove existing foreign key
            $table->dropForeign([
                'requested_by'
            ]);

        });

        Schema::table('travel_requests', function (Blueprint $table) {

            // Change requested_by from bigint to varchar
            $table->string('requested_by', 255)
                ->nullable()
                ->change();

        });
    }

    public function down(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {

            // Change back to bigint
            $table->unsignedBigInteger('requested_by')
                ->nullable()
                ->change();

            // Restore foreign key
            $table->foreign('requested_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

        });
    }
};