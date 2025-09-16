<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Purchased Subscriptions
        Schema::table('talentrek_purchased_subscriptions', function (Blueprint $table) {
            $table->string('coupon_type', 50)->nullable()->after('tax');
            $table->string('coupon_code', 100)->nullable()->after('coupon_type');
            $table->decimal('coupon_amount', 10, 2)->default(0.00)->after('coupon_code');
            $table->string('order_id', 100)->nullable()->after('coupon_amount');
        });

        // 2. Training Material Purchases
        Schema::table('talentrek_jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->string('coupon_type', 50)->nullable()->after('total_amount');
            $table->string('coupon_code', 100)->nullable()->after('coupon_type');
            $table->decimal('coupon_amount', 10, 2)->default(0.00)->after('coupon_code');
            $table->string('order_id', 100)->nullable()->after('coupon_amount');
        });

        // 3. Saved Booking Session
        Schema::table('talentrek_jobseeker_saved_booking_session', function (Blueprint $table) {
            $table->string('coupon_type', 50)->nullable()->after('total_amount');
            $table->string('coupon_code', 100)->nullable()->after('coupon_type');
            $table->decimal('coupon_amount', 10, 2)->default(0.00)->after('coupon_code');
            $table->string('order_id', 100)->nullable()->after('coupon_amount');
        });
    }

    public function down(): void
    {
        Schema::table('talentrek_purchased_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['coupon_type','coupon_code','coupon_amount','order_id']);
        });

        Schema::table('talentrek_jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->dropColumn(['coupon_type','coupon_code','coupon_amount','order_id']);
        });

        Schema::table('talentrek_jobseeker_saved_booking_session', function (Blueprint $table) {
            $table->dropColumn(['coupon_type','coupon_code','coupon_amount','order_id']);
        });
    }
};
