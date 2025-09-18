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
        Schema::create('jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->integer('jobseeker_id')->nullable();
            $table->integer('trainer_id')->nullable();
            $table->integer('material_id')->nullable();
            $table->integer('purchased_by')->nullable();

            // Nullable enum types
            $table->enum('training_type', ['online', 'classroom', 'recorded'])->nullable();
            $table->enum('session_type', ['online', 'classroom'])->nullable();

            // Nullable batch
            $table->string('batch_id')->nullable();

            // Purchase for type
            $table->enum('purchase_for', ['individual', 'team']);

            // Payment relation
            $table->string('payment_id')->nullable();

            // Status
            $table->string('batchStatus')->nullable();
            $table->string('status')->default('pending');

            // Billing fields
            $table->string('tax_percentage')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('taxed_amount')->nullable(); 
            $table->decimal('amount_paid', 10, 2);


            $table->string('coupon_type')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_code')->nullable();         // CAPTURED, DECLINED, etc.
            $table->string('coupon_amount')->nullable();   
            $table->string('order_id')->nullable();         // CAPTURED, DECLINED, etc.


            $table->string('track_id', length: 50)->comment('Unique booking reference number');
            $table->unique('track_id', 'booking_track_id_unique');
            $table->string('transaction_id', 191)->nullable()->comment('from payment provider');
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('response_payload')->nullable()->comment('full response from gateway');


            // Team members count
            $table->unsignedInteger('member_count')->nullable();

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
        Schema::dropIfExists('jobseeker_training_material_purchases');
    }
};

