<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ============================
        // Jobseeker Booking Payment Request
        // ============================
        Schema::create('talentrek_jobseeker_sessions_booking_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Reservation Info
            $table->unsignedBigInteger('jobseeker_id');
            $table->enum('user_type', ['mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_slot_id');
            $table->string('slot_mode', 191)->nullable();
            $table->date('slot_date');
            $table->string('slot_time', 191);
            $table->enum('status', ['awaiting_payment', 'confirmed', 'cancelled', 'expired'])->default('awaiting_payment');
            $table->timestamp('reserved_until')->nullable();
            $table->string('track_id', 50)->unique()->comment('Unique booking reference number');

            // Payment Info
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 10)->default('SAR');
            $table->string('payment_gateway', 50)->default('Al-Rajhi');
            $table->json('request_payload')->nullable()->comment('full request sent to gateway');
            $table->string('transaction_id', 191)->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('response_payload')->nullable();

            $table->timestamps();
        });

        // ============================
        // Saved Booking Session (alter)
        // ============================
        Schema::table('talentrek_jobseeker_saved_booking_session', function (Blueprint $table) {
            $table->string('track_id', 100)->nullable()->after('booking_slot_id');
            $table->string('transaction_id', 191)->nullable()->after('track_id');
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending')->after('transaction_id');
            $table->json('response_payload')->nullable()->after('payment_status');
            $table->decimal('amount', 10, 2)->default(0.00)->after('response_payload');
            $table->decimal('tax', 10, 2)->default(0.00)->after('amount');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('tax');
        });

        // ============================
        // Subscription Payment Request
        // ============================
        Schema::create('talentrek_purchase_subscriptions_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 50);
            $table->enum('status', ['pending', 'active', 'cancelled', 'failed'])->default('pending');
            $table->string('track_id', 100)->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('SAR');
            $table->string('payment_gateway', 50)->default('Al Rajhi');
            $table->json('request_payload')->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['initiated', 'success', 'failed', 'refunded'])->default('initiated');
            $table->json('response_payload')->nullable();
            $table->timestamps();
        });

        // ============================
        // Purchased Subscriptions (alter)
        // ============================
        Schema::table('talentrek_purchased_subscriptions', function (Blueprint $table) {
            $table->string('track_id', 100)->nullable()->after('payment_status');
            $table->string('transaction_id', 191)->nullable()->after('track_id');
            $table->json('request_payload')->nullable()->after('payment_status');
            $table->json('response_payload')->nullable()->after('payment_status');
            $table->decimal('amount', 10, 2)->default(0.00)->after('response_payload');
            $table->decimal('tax', 10, 2)->default(0.00)->after('amount');
        });

        // ============================
        // Training Material Purchase Payment Request
        // ============================
        Schema::create('talentrek_jobseeker_training_material_purchases_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->json('request_payload')->nullable();
            $table->string('track_id', 100)->unique();
            $table->enum('type', ['buyNow', 'buyForCorporate', 'cart']);
            $table->string('training_type', 50);
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['initiated', 'success', 'failed', 'refunded'])->default('initiated');
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->string('payment_gateway', 50);
            $table->timestamps();

            $table->index('jobseeker_id', 'idx_jobseeker');
            $table->index('trainer_id', 'idx_trainer');
            $table->index('material_id', 'idx_material');
            $table->index('batch_id', 'idx_batch');
        });

        // ============================
        // Training Material Purchases (alter)
        // ============================
        Schema::table('talentrek_jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->string('track_id', 100)->nullable()->after('transaction_id');
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending')->after('transaction_id');
            $table->json('response_payload')->nullable()->after('payment_status');
            $table->decimal('amount', 10, 2)->default(0.00)->after('response_payload');
            $table->decimal('tax', 10, 2)->default(0.00)->after('amount');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('tax');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talentrek_jobseeker_sessions_booking_payment_request');
        Schema::dropIfExists('talentrek_purchase_subscriptions_payment_request');
        Schema::dropIfExists('talentrek_jobseeker_training_material_purchases_payment_request');

        Schema::table('talentrek_jobseeker_saved_booking_session', function (Blueprint $table) {
            $table->dropColumn(['track_id','transaction_id','payment_status','response_payload','amount','tax','total_amount']);
        });

        Schema::table('talentrek_purchased_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['track_id','transaction_id','request_payload','response_payload','amount','tax']);
        });

        Schema::table('talentrek_jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->dropColumn(['track_id','payment_status','response_payload','amount','tax','total_amount']);
        });
    }
};
