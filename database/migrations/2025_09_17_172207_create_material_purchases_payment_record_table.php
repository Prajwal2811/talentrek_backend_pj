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
        Schema::create('material_purchases_payment_record', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->string('jobseeker_id')->nullable();
            $table->string('trainer_id')->nullable();
            $table->string('material_id')->nullable();

            
            // Nullable enum types
            $table->enum('training_type', ['online', 'classroom', 'recorded'])->nullable();
            $table->enum('session_type', ['online', 'classroom'])->nullable();

            // Nullable batch
            $table->string('batch_id')->nullable();

            // Purchase for type

            $table->enum('purchase_for', ['individual', 'team', 'cart']);

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
        Schema::dropIfExists('material_purchases_payment_record');
    }
};





// ALTER TABLE talentrek_material_purchases_payment_record
// ADD COLUMN IF NOT EXISTS jobseeker_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS trainer_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS material_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS training_type ENUM('online', 'classroom', 'recorded') NULL,
// ADD COLUMN IF NOT EXISTS session_type ENUM('online', 'classroom') NULL,
// ADD COLUMN IF NOT EXISTS batch_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS purchase_for ENUM('individual', 'team', 'cart') NULL,
// ADD COLUMN IF NOT EXISTS payment_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS batchStatus VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS status VARCHAR(255) NOT NULL DEFAULT 'pending',
// ADD COLUMN IF NOT EXISTS tax_percentage VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS taxed_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS amount_paid DECIMAL(10,2) NULL,
// ADD COLUMN IF NOT EXISTS coupon_type VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS order_id VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS track_id VARCHAR(50) NULL COMMENT 'Unique booking reference number',
// ADD UNIQUE INDEX IF NOT EXISTS booking_track_id_unique (track_id),
// ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(191) NULL COMMENT 'from payment provider',
// ADD COLUMN IF NOT EXISTS payment_status ENUM('pending', 'success', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
// ADD COLUMN IF NOT EXISTS response_payload JSON NULL COMMENT 'full response from gateway',
// ADD COLUMN IF NOT EXISTS member_count INT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT NULL,
// ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL;