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

            /*
            |--------------------------------------------------------------------------
            | Travel Request Information
            |--------------------------------------------------------------------------
            */
            $table->string('employee_email', 255)
                ->nullable()
                ->after('requested_by');

            $table->string('travel_id', 100)
                ->nullable()
                ->after('employee_email');

            $table->string('trip_id', 100)
                ->nullable()
                ->after('travel_id');

            $table->string('vendor_name', 255)
                ->nullable()
                ->after('trip_id');


            /*
            |--------------------------------------------------------------------------
            | Vehicle / Travel Details
            |--------------------------------------------------------------------------
            */
            $table->string('vehicle_type', 100)
                ->nullable()
                ->after('vendor_name');

            $table->date('travel_from_date')
                ->nullable()
                ->after('vehicle_type');

            $table->date('travel_to_date')
                ->nullable()
                ->after('travel_from_date');

            $table->time('pickup_time')
                ->nullable()
                ->after('travel_to_date');

            $table->string('from_city', 255)
                ->nullable()
                ->after('pickup_time');


            /*
            |--------------------------------------------------------------------------
            | Release / Reporting Details
            |--------------------------------------------------------------------------
            */

            $table->string('release_location', 255)
                ->nullable()
                ->after('drop_location');

            $table->text('reporting_address')
                ->nullable()
                ->after('release_location');

            $table->text('release_address')
                ->nullable()
                ->after('reporting_address');

            $table->time('release_time')
                ->nullable()
                ->after('release_address');


            /*
            |--------------------------------------------------------------------------
            | Passenger / Employee Details
            |--------------------------------------------------------------------------
            */

            $table->string('traveler_mobile', 20)
                ->nullable()
                ->after('passenger_phone');

            $table->string('employee_id', 100)
                ->nullable()
                ->after('traveler_mobile');


            /*
            |--------------------------------------------------------------------------
            | Billing / Usage Details
            |--------------------------------------------------------------------------
            */

            $table->string('cost_center', 100)
                ->nullable()
                ->after('employee_id');

            $table->string('car_hire_type', 50)
                ->nullable()
                ->after('cost_center');

            $table->string('for_use', 100)
                ->nullable()
                ->after('car_hire_type');

            $table->string('gst_number', 20)
                ->nullable()
                ->after('for_use');


            /*
            |--------------------------------------------------------------------------
            | Instructions
            |--------------------------------------------------------------------------
            */

            $table->text('specific_instruction')
                ->nullable()
                ->after('purpose');


            /*
            |--------------------------------------------------------------------------
            | Delete Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('deleted_by')
                ->nullable()
                ->after('updated_by')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Drop Foreign Key
            |--------------------------------------------------------------------------
            */

            $table->dropForeign(['deleted_by']);


            /*
            |--------------------------------------------------------------------------
            | Drop Added Columns
            |--------------------------------------------------------------------------
            */

            $table->dropColumn([
                'employee_email',
                'travel_id',
                'trip_id',
                'vendor_name',
                'vehicle_type',
                'travel_from_date',
                'travel_to_date',
                'pickup_time',
                'from_city',
                'release_location',
                'reporting_address',
                'release_address',
                'release_time',
                'traveler_mobile',
                'employee_id',
                'cost_center',
                'car_hire_type',
                'for_use',
                'gst_number',
                'specific_instruction',
                'deleted_by',
            ]);
        });
    }
};