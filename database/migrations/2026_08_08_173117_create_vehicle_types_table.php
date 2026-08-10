<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {

            $table->id();

            $table->foreignId('vehicle_category_id')
                ->constrained('vehicle_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 100);

            $table->string('code', 50);

            $table->text('description')
                ->nullable();

            $table->boolean('status')
                ->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE TYPE CODE INSIDE CATEGORY
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'vehicle_category_id',
                'code'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};