
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
        Schema::table('duty_slips', function (Blueprint $table) {
            $table->string('request_slip_front_file')
                ->nullable()
                ->after('duty_slip_back_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('duty_slips', function (Blueprint $table) {
            $table->dropColumn('request_slip_front_file');
        });
    }
};
