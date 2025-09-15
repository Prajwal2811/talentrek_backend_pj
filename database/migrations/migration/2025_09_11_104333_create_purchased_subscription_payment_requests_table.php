<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchased_subscription_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_plan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->string('status')->default('pending');
            $table->string('track_id')->unique('psp_req_track_id_unique'); // Short unique name
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 10)->default('AED');
            $table->string('payment_gateway')->nullable();
            $table->longText('request_payload')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->default('initiated');
            $table->longText('response_payload')->nullable();
            $table->timestamps();

            // Short foreign key name
            $table->foreign('subscription_plan_id', 'psp_req_plan_id_fk')
                  ->references('id')->on('subscription_plans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchased_subscription_payment_requests');
    }
};
