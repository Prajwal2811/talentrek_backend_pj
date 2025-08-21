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
            // Polymorphic relation: jobseeker, trainer, etc.
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            // Dates
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Optional: payment status/amount if needed
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('payment_status')->nullable(); // or 'paid', 'failed', etc.

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
