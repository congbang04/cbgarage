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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('lock_expires_at')->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('description')->nullable();

            $table->foreignId('bay_id')
                ->nullable()
                ->constrained('bays');

            $table->foreignId('customer_id')
                ->constrained('customers', 'user_id');

            $table->foreignId('car_id')
                ->constrained('cars');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
