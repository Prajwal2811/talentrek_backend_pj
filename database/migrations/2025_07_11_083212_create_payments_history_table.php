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
            $table->string('tax_percentage')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('taxed_amount')->nullable();         // CAPTURED, DECLINED, etc.
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







// ALTER TABLE talentrek_payments_history
// ADD COLUMN IF NOT EXISTS user_type ENUM('jobseeker', 'trainer', 'mentor', 'coach', 'assessor', 'recruiter', 'expat') NULL,
// ADD COLUMN IF NOT EXISTS user_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS receiver_type ENUM('trainer', 'mentor', 'coach', 'assessor', 'recruiter', 'expat', 'talentrek') NULL,
// ADD COLUMN IF NOT EXISTS receiver_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS payment_for ENUM('training', 'booking_slot', 'subscription') NULL,
// ADD COLUMN IF NOT EXISTS amount_paid DECIMAL(10,2) NULL,
// ADD COLUMN IF NOT EXISTS tax_percentage VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS taxed_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS applied_coupon VARCHAR(255) NULL COMMENT 'coupon code if applied',
// ADD COLUMN IF NOT EXISTS payment_status ENUM('pending', 'completed', 'failed', 'refunded') NULL,
// ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS track_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS order_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS currency VARCHAR(10) NULL DEFAULT 'INR',
// ADD COLUMN IF NOT EXISTS payment_method VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS paid_at DATETIME NULL,
// ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT NULL,
// ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL;
