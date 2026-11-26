
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
        | Add Processing Number Column
        |--------------------------------------------------------------------------
        */

        Schema::table('salary_processings', function (Blueprint $table) {

            $table->string('processing_no', 40)
                ->nullable()
                ->after('id');

        });

        /*
        |--------------------------------------------------------------------------
        | Generate Processing Numbers For Existing Records
        |--------------------------------------------------------------------------
        |
        | Format: SAL-YYYYMM-000001
        |
        */

        DB::table('salary_processings')
            ->select([
                'id',
                'salary_month',
                'salary_year',
            ])
            ->orderBy('id')
            ->chunkById(500, function ($records) {

                foreach ($records as $record) {

                    $processingNo = sprintf(
                        'SAL-%04d%02d-%06d',
                        (int) $record->salary_year,
                        (int) $record->salary_month,
                        (int) $record->id
                    );

                    DB::table('salary_processings')
                        ->where('id', $record->id)
                        ->update([
                            'processing_no' => $processingNo,
                        ]);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Make Processing Number NOT NULL
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE `salary_processings`
            MODIFY COLUMN `processing_no` VARCHAR(40) NOT NULL
        ");

        /*
        |--------------------------------------------------------------------------
        | Unique Index
        |--------------------------------------------------------------------------
        */

        Schema::table('salary_processings', function (Blueprint $table) {

            $table->unique(
                'processing_no',
                'salary_processings_processing_no_unique'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_processings', function (Blueprint $table) {

            $table->dropUnique(
                'salary_processings_processing_no_unique'
            );

            $table->dropColumn('processing_no');

        });
    }
};
