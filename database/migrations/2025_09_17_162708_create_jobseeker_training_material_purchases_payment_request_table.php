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
        Schema::create('jobseeker_training_material_purchases_payment_request', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign keys (not enforced yet)
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('batch_id')->nullable();

            // Payment request details
            $table->json('request_payload')->nullable();

            // Shortened unique index for track_id
            $table->string('track_id', 100)->comment('Unique track/reference ID');
            $table->unique('track_id', 'training_track_id_unique');

            $table->enum('type', ['buyNow', 'buyForCorporate', 'cart']);
            $table->string('training_type', 50); // online/classroom/recorded
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['initiated', 'success', 'failed', 'refunded'])->default('initiated');

            // Amounts
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('amount', 10, 2)->default(0.00);       // base amount
            $table->decimal('amount_paid', 10, 2)->default(0.00);  // after discount/tax

            $table->string('currency', 10)->default('INR');
            $table->string('payment_gateway', 50);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('jobseeker_id', 'idx_jobseeker');
            $table->index('trainer_id', 'idx_trainer');
            $table->index('material_id', 'idx_material');
            $table->index('batch_id', 'idx_batch');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_training_material_purchases_payment_request');
    }

};
