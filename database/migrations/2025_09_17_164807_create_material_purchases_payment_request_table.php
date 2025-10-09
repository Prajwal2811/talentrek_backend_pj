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
        Schema::create('material_purchases_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign keys (not enforced yet)
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();

            // Payment request details
            $table->json('request_payload')->nullable();

            // Shortened unique index for track_id
            $table->string('track_id', 100)->nullable()->comment('Unique track/reference ID');
            $table->unique('track_id', 'training_track_id_unique');

            $table->enum('type', ['buyNow', 'buyForCorporate', 'cart']);
            $table->string('training_type', 50)->nullable(); // online/classroom/recorded
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['initiated', 'success', 'failed', 'refunded'])->default('initiated');

            // Amounts
            $table->decimal('taxed_amount', 10, 2)->default(0.00)->nullable();
            $table->decimal('tax_percentage', 10, 2)->default(0.00)->nullable();


            $table->decimal('amount', 10, 2)->default(0.00)->nullable();       // base amount
            $table->decimal('amount_paid', 10, 2)->default(0.00)->nullable();  // after discount/tax

            $table->string('currency', 10)->default('SAR')->nullable();
            $table->string('payment_gateway', 50)->nullable();


            $table->string('coupon_type')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_code')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_amount')->nullable();   

            // Timestamps
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
        Schema::dropIfExists('material_purchases_payment_request');
    }

};
