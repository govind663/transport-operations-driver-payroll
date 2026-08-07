<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('client_code',30)->unique();
            $table->string('company_name',200);
            $table->string('contact_person',150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Company Logo
            |--------------------------------------------------------------------------
            */
            $table->string('company_logo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */
            $table->string('mobile',20)->nullable();
            $table->string('alternate_mobile',20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            /*
            |--------------------------------------------------------------------------
            | GST Information
            |--------------------------------------------------------------------------
            */
            $table->string('gst_number',20)->nullable();
            $table->string('pan_number',20)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */
            $table->text('address')->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('country',100)->default('India');
            $table->string('pincode',10)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Billing Address
            |--------------------------------------------------------------------------
            */
            $table->text('billing_address')->nullable();
            $table->string('billing_city',100)->nullable();
            $table->string('billing_state',100)->nullable();
            $table->string('billing_country',100)->default('India');
            $table->string('billing_pincode',10)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('status')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit Columns
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

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('company_name');
            $table->index('contact_person');
            $table->index('mobile');
            $table->index('email');
            $table->index('gst_number');
            $table->index('city');
            $table->index('state');
            $table->index('status');
            $table->index(['company_name','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};