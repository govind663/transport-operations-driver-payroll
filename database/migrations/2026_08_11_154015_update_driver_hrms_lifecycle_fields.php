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
        /*
        |--------------------------------------------------------------------------
        | Remove Existing Employment Columns
        |--------------------------------------------------------------------------
        */

        Schema::table('drivers', function (Blueprint $table) {

            $columns = [
                'joining_date',
                'resignation_date',
                'last_working_date',
                'termination_date',
            ];

            /*
            |--------------------------------------------------------------------------
            | Drop Employment Date Columns If Exist
            |--------------------------------------------------------------------------
            */

            foreach ($columns as $column) {

                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Remove Existing Status Column
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('drivers', 'status')) {

            Schema::table('drivers', function (Blueprint $table) {

                $table->dropColumn('status');

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Add Employment Columns With Correct Datatypes
        |--------------------------------------------------------------------------
        */

        Schema::table('drivers', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Joining Date
            |--------------------------------------------------------------------------
            */

            $table->date('joining_date')
                ->nullable()
                ->after('date_of_birth');


            /*
            |--------------------------------------------------------------------------
            | Resignation Date
            |--------------------------------------------------------------------------
            */

            $table->date('resignation_date')
                ->nullable()
                ->after('joining_date');


            /*
            |--------------------------------------------------------------------------
            | Last Working Date
            |--------------------------------------------------------------------------
            */

            $table->date('last_working_date')
                ->nullable()
                ->after('resignation_date');


            /*
            |--------------------------------------------------------------------------
            | Termination Date
            |--------------------------------------------------------------------------
            */

            $table->date('termination_date')
                ->nullable()
                ->after('last_working_date');


            /*
            |--------------------------------------------------------------------------
            | Employment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'active',
                'on_leave',
                'notice_period',
                'resigned',
                'terminated',
                'inactive',

            ])
                ->default('active')
                ->after('termination_date');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Remove Current Employment Columns
        |--------------------------------------------------------------------------
        */

        Schema::table('drivers', function (Blueprint $table) {

            $columns = [
                'joining_date',
                'resignation_date',
                'last_working_date',
                'termination_date',
                'status',
            ];

            foreach ($columns as $column) {

                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Restore Original Columns
        |--------------------------------------------------------------------------
        |
        | Employment date columns are restored as nullable DATE fields.
        |
        */

        Schema::table('drivers', function (Blueprint $table) {

            $table->date('joining_date')
                ->nullable()
                ->after('date_of_birth');

            $table->date('resignation_date')
                ->nullable()
                ->after('joining_date');

            $table->date('last_working_date')
                ->nullable()
                ->after('resignation_date');

            $table->date('termination_date')
                ->nullable()
                ->after('last_working_date');


            /*
            |--------------------------------------------------------------------------
            | Restore Status
            |--------------------------------------------------------------------------
            |
            | Original status assumed to be tinyInteger.
            |
            */

            $table->tinyInteger('status')
                ->default(1)
                ->after('termination_date');

        });
    }
};