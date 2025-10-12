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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('booking_slot_id')->nullable();

            $table->string('slot_mode', 191)->nullable();
            $table->date('slot_date');
            $table->string('slot_time', 191);
            $table->enum('status', ['awaiting_payment', 'confirmed', 'cancelled', 'expired'])
                ->default('awaiting_payment');
            $table->timestamp('reserved_until')->nullable();

            // Payment Info
            $table->string('track_id')->nullable()->comment('Unique booking reference number');
            $table->unique('track_id', 'booking_track_id_unique');


            $table->decimal('amount', 10, 2)->comment('Base session amount');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('Applied tax rate %');
            $table->decimal('tax', 10, 2)->default(0)->comment('Calculated tax value');
            $table->decimal('taxed_amount', 10, 2)->default(0)->comment('Amount after tax but before coupons');
            $table->decimal('total_amount', 10, 2)->comment('Final amount after tax & coupon');

            // New fields
            $table->decimal('amount_paid', 10, 2)->default(0)->comment('Amount actually paid by jobseeker');
            $table->string('order_id', 191)->nullable()->comment('Payment gateway order ID');

            // Coupon Info
            $table->enum('coupon_type', ['fixed', 'percentage'])->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('coupon_amount', 10, 2)->default(0);

            $table->string('currency', 10)->default('SAR');
            $table->string('payment_gateway', 50)->default('Al-Rajhi');
            $table->json('request_payload')->nullable()->comment('full request sent to gateway');
            $table->string('transaction_id', 191)->nullable()->comment('from payment provider');
            $table->string('payment_status')->default('initiated');
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




// ALTER TABLE talentrek_jobseeker_sessions_booking_payment_request
// ADD COLUMN IF NOT EXISTS jobseeker_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS user_type ENUM('mentor', 'coach', 'assessor') NULL,
// ADD COLUMN IF NOT EXISTS user_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS booking_slot_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS slot_mode VARCHAR(191) NULL,
// ADD COLUMN IF NOT EXISTS slot_date DATE NULL,
// ADD COLUMN IF NOT EXISTS slot_time VARCHAR(191) NULL,
// ADD COLUMN IF NOT EXISTS status ENUM('awaiting_payment', 'confirmed', 'cancelled', 'expired') NOT NULL DEFAULT 'awaiting_payment',
// ADD COLUMN IF NOT EXISTS reserved_until TIMESTAMP NULL,
// ADD COLUMN IF NOT EXISTS track_id VARCHAR(255) NULL COMMENT 'Unique booking reference number',
// ADD UNIQUE INDEX IF NOT EXISTS booking_track_id_unique (track_id),
// ADD COLUMN IF NOT EXISTS amount DECIMAL(10,2) NULL COMMENT 'Base session amount',
// ADD COLUMN IF NOT EXISTS tax_percentage DECIMAL(5,2) NOT NULL DEFAULT 0 COMMENT 'Applied tax rate %',
// ADD COLUMN IF NOT EXISTS tax DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Calculated tax value',
// ADD COLUMN IF NOT EXISTS taxed_amount DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Amount after tax but before coupons',
// ADD COLUMN IF NOT EXISTS total_amount DECIMAL(10,2) NULL COMMENT 'Final amount after tax & coupon',
// ADD COLUMN IF NOT EXISTS amount_paid DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Amount actually paid by jobseeker',
// ADD COLUMN IF NOT EXISTS order_id VARCHAR(191) NULL COMMENT 'Payment gateway order ID',
// ADD COLUMN IF NOT EXISTS coupon_type ENUM('fixed', 'percentage') NULL,
// ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(50) NULL,
// ADD COLUMN IF NOT EXISTS coupon_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
// ADD COLUMN IF NOT EXISTS currency VARCHAR(10) NOT NULL DEFAULT 'SAR',
// ADD COLUMN IF NOT EXISTS payment_gateway VARCHAR(50) NOT NULL DEFAULT 'Al-Rajhi',
// ADD COLUMN IF NOT EXISTS request_payload JSON NULL COMMENT 'full request sent to gateway',
// ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(191) NULL COMMENT 'from payment provider',
// ADD COLUMN IF NOT EXISTS payment_status VARCHAR(255) NOT NULL DEFAULT 'initiated',
// ADD COLUMN IF NOT EXISTS response_payload JSON NULL COMMENT 'full response from gateway',
// ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT NULL,
// ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL;
