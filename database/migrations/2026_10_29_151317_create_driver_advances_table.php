
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
        Schema::create('driver_advances', function (Blueprint $table) {
            $table->id();

            // Driver and Duty Slip relationships
            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->cascadeOnUpdate();

            $table->foreignId('duty_slip_id')
                ->constrained('duty_slips')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Advance details
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('remarks')->nullable();

            // Advance approval/payment status
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'paid',
                'cancelled',
            ])->default('pending');

            // Audit fields
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Indexes
            $table->index(
                ['driver_id', 'duty_slip_id'],
                'driver_advances_driver_slip_index'
            );

            $table->index('status', 'driver_advances_status_index');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_advances');
    }
};
