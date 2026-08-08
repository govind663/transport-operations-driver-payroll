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
        Schema::create('drivers', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Driver Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('driver_code', 30)->unique();

            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();

            $table->string('father_name', 150)->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('gender', 20)->nullable();

            $table->string('marital_status', 30)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */
            $table->string('mobile', 15)->nullable();
            $table->string('alternate_mobile', 15)->nullable();

            $table->string('email', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address Information
            |--------------------------------------------------------------------------
            */
            $table->text('address')->nullable();

            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('India');
            $table->string('pincode', 10)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Driving Licence Information
            |--------------------------------------------------------------------------
            */
            $table->string('license_number', 50)->nullable()->unique();

            $table->string('license_type', 100)->nullable();

            $table->date('license_issue_date')->nullable();

            $table->date('license_expiry_date')->nullable();

            $table->string('license_issuing_authority', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */
            $table->string('driver_photo')->nullable();

            $table->string('license_document')->nullable();

            $table->string('aadhar_number', 20)->nullable();

            $table->string('aadhar_document')->nullable();

            $table->string('pan_number', 20)->nullable();

            $table->string('pan_document')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('status')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Timestamps + Soft Delete
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};