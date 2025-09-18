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
        Schema::create('corporates_emailids', function (Blueprint $table) {
            $table->id();

            // Store multiple email ids as JSON
            $table->json('corporatesEmailIds')->nullable();

            // Foreign keys (not enforced but kept as references)
            $table->unsignedBigInteger('paymentRequestId')->nullable();
            $table->unsignedBigInteger('successPaymentId')->nullable();

            // Track reference
            $table->string('track_id', 100)->nullable();

            $table->timestamps();

            // Indexes
            $table->index('paymentRequestId', 'idx_payment_request');
            $table->index('successPaymentId', 'idx_success_payment');
            $table->index('track_id', 'idx_track_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporates_emailids');
    }
};
