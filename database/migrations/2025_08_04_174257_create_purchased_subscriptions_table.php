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
        Schema::create('purchased_subscriptions', function (Blueprint $table) {
            $table->id();

            // Link to the subscription plan
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');

            // Polymorphic relation: jobseeker, recruiter, trainer, mentor, coach, assessor, expat
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('user_type', [
                'jobseeker',
                'recruiter',
                'trainer',
                'mentor',
                'coach',
                'assessor',
                'expat'
            ]);

            // For recruiters only (nullable for others)
            $table->unsignedBigInteger('company_id')->nullable();

            // Dates
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Payment info
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('payment_status')->nullable(); // e.g. 'paid', 'pending', 'failed'

            // Extra payment gateway details
            $table->string('transaction_id')->nullable(); // tranid from gateway
            $table->string('payment_id')->nullable();     // paymentid from gateway
            $table->string('track_id')->nullable();       // trackid from gateway
            $table->string('order_id')->nullable();       // our udf4
            $table->string('currency', 10)->nullable();   // SAR, USD etc.
            $table->string('result')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_type')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_code')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_amount')->nullable();         // CAPTURED, DECLINED, etc.
            $table->longText('response_payload')->nullable(); // store full gateway JSON
            $table->longText('raw_response')->nullable(); // store full gateway JSON
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
        Schema::dropIfExists('purchased_subscriptions');
    }
};
