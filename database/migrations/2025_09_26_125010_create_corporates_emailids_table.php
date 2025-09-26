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
            $table->json('corporatesEmailIds')->nullable(); // JSON array of emails
            $table->unsignedBigInteger('paymentRequestId')->nullable();
            $table->unsignedBigInteger('successPaymentId')->nullable();
            $table->string('track_id')->nullable()->unique();
            $table->timestamps();

            // Optional: add foreign keys if the related tables exist
            // $table->foreign('paymentRequestId')->references('id')->on('purchased_subscription_payment_requests')->onDelete('set null');
            // $table->foreign('successPaymentId')->references('id')->on('purchased_subscriptions')->onDelete('set null');
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
