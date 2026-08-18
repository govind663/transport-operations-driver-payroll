<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {

            if (!Schema::hasColumn('drivers', 'state')) {
                $table->string('state', 100)
                    ->nullable()
                    ->after('city');
            }

            if (!Schema::hasColumn('drivers', 'country')) {
                $table->string('country', 100)
                    ->nullable()
                    ->after('state');
            }

            if (!Schema::hasColumn('drivers', 'pincode')) {
                $table->string('pincode', 10)
                    ->nullable()
                    ->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('drivers', 'state')) {
                $columns[] = 'state';
            }

            if (Schema::hasColumn('drivers', 'country')) {
                $columns[] = 'country';
            }

            if (Schema::hasColumn('drivers', 'pincode')) {
                $columns[] = 'pincode';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};