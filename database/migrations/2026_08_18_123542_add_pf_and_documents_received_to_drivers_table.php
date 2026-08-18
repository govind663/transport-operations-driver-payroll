<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PF Status
            |--------------------------------------------------------------------------
            */
            $table->enum(
                'pf_status',
                [
                    'yes',
                    'no',
                ]
            )
            ->default('no')
            ->after('status');


            /*
            |--------------------------------------------------------------------------
            | Document Status
            |--------------------------------------------------------------------------
            */
            $table->enum(
                'document_status',
                [
                    'received',
                    'pending',
                    'rejected',
                ]
            )
            ->default('pending')
            ->after('pf_status');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {

            $table->dropColumn([
                'pf_status',
                'document_status',
            ]);
        });
    }
};