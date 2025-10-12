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
            $table->decimal('actual_amount', 8, 2)->nullable();
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
            $table->string('tax_percentage')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('taxed_amount')->nullable();         // CAPTURED, DECLINED, etc.
            $table->longText('response_payload')->nullable(); // store full gateway JSON
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





// ALTER TABLE talentrek_purchased_subscriptions
// ADD COLUMN IF NOT EXISTS subscription_plan_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS user_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS user_type ENUM('jobseeker', 'recruiter', 'trainer', 'mentor', 'coach', 'assessor', 'expat') NULL,
// ADD COLUMN IF NOT EXISTS company_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS start_date DATE NULL,
// ADD COLUMN IF NOT EXISTS end_date DATE NULL,
// ADD COLUMN IF NOT EXISTS actual_amount DECIMAL(8,2) NULL,
// ADD COLUMN IF NOT EXISTS amount_paid DECIMAL(8,2) NULL,
// ADD COLUMN IF NOT EXISTS payment_status VARCHAR(255) NULL COMMENT 'e.g. paid, pending, failed',
// ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(255) NULL COMMENT 'tranid from gateway',
// ADD COLUMN IF NOT EXISTS payment_id VARCHAR(255) NULL COMMENT 'paymentid from gateway',
// ADD COLUMN IF NOT EXISTS track_id VARCHAR(255) NULL COMMENT 'trackid from gateway',
// ADD COLUMN IF NOT EXISTS order_id VARCHAR(255) NULL COMMENT 'our udf4',
// ADD COLUMN IF NOT EXISTS currency VARCHAR(10) NULL COMMENT 'SAR, USD etc.',
// ADD COLUMN IF NOT EXISTS result VARCHAR(255) NULL COMMENT 'CAPTURED, DECLINED, etc.',
// ADD COLUMN IF NOT EXISTS coupon_type VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS tax_percentage VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS taxed_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS response_payload LONGTEXT NULL COMMENT 'store full gateway JSON',
// ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT NULL,
// ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL;
