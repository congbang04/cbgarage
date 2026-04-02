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
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bay_id')
                ->constrained('bays')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers');

            $table->timestamp('start_time');
            $table->timestamp('end_time');

            $table->timestamp('expires_at');
            $table->uuid('session_id')->nullable()->index();
            $table->timestamps();

            $table->index(['bay_id', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_locks');
    }
};
