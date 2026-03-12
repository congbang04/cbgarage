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
            $table->unsignedBigInteger('service_id');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status', 50)->default('pending');
            $table->text('description')->nullable();

            $table->foreignId('bay_id')
                ->nullable()
                ->constrained('bays');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers', 'user_id');

            $table->foreignId('car_id')
                ->nullable()
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
