<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payments_history', function (Blueprint $table) {
            $table->id();

            // Who paid
            $table->enum('user_type', ['jobseeker', 'trainer', 'mentor', 'coach', 'assessor', 'recruiter', 'expat']);
            $table->unsignedBigInteger('user_id')->nullable();

            // Who received payment
            $table->enum('receiver_type', [
                'trainer', 'mentor', 'coach', 'assessor', 'recruiter', 'expat', 'talentrek'
            ])->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();

            // Purpose
            $table->enum('payment_for', ['training', 'booking_slot', 'subscription']);

            // Payment details
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->default(0.00); // tax amount
            $table->string('applied_coupon')->nullable(); // coupon code if applied
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded']);
            $table->string('transaction_id')->nullable();
            $table->string('track_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('payment_method')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
