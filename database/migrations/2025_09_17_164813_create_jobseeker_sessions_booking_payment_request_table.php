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
        Schema::create('jobseeker_sessions_booking_payment_request', function (Blueprint $table) {
            $table->id();

            // Reservation Info
            $table->unsignedBigInteger('jobseeker_id');
            $table->enum('user_type', ['mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_slot_id');
            $table->string('slot_mode', 191)->nullable();
            $table->date('slot_date');
            $table->string('slot_time', 191);
            $table->enum('status', ['awaiting_payment', 'confirmed', 'cancelled', 'expired'])
                  ->default('awaiting_payment');
            $table->timestamp('reserved_until')->nullable();

            // Payment Info
            $table->string('track_id', 50)->comment('Unique booking reference number');
            $table->unique('track_id', 'booking_track_id_unique');

            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 10)->default('SAR');
            $table->string('payment_gateway', 50)->default('Al-Rajhi');
            $table->json('request_payload')->nullable()->comment('full request sent to gateway');
            $table->string('transaction_id', 191)->nullable()->comment('from payment provider');
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('response_payload')->nullable()->comment('full response from gateway');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseeker_sessions_booking_payment_request');
    }
};
