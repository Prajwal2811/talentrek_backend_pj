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
                $table->string('user_type')->nullable(); // 'jobseeker' or 'expat'
                // Foreign keys (not enforced yet)
                $table->unsignedBigInteger('jobseeker_id')->nullable();
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






// ALTER TABLE talentrek_material_purchases_payment_request
// ADD COLUMN IF NOT EXISTS jobseeker_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS trainer_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS material_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS batch_id BIGINT UNSIGNED NULL,
// ADD COLUMN IF NOT EXISTS request_payload JSON NULL,
// ADD COLUMN IF NOT EXISTS track_id VARCHAR(100) NULL COMMENT 'Unique track/reference ID',
// ADD UNIQUE INDEX IF NOT EXISTS training_track_id_unique (track_id),
// ADD COLUMN IF NOT EXISTS type ENUM('buyNow', 'buyForCorporate', 'cart') NULL,
// ADD COLUMN IF NOT EXISTS training_type VARCHAR(50) NULL COMMENT 'online/classroom/recorded',
// ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(100) NULL,
// ADD COLUMN IF NOT EXISTS payment_status ENUM('initiated', 'success', 'failed', 'refunded') NOT NULL DEFAULT 'initiated',
// ADD COLUMN IF NOT EXISTS taxed_amount DECIMAL(10,2) NULL DEFAULT 0.00,
// ADD COLUMN IF NOT EXISTS tax_percentage DECIMAL(10,2) NULL DEFAULT 0.00,
// ADD COLUMN IF NOT EXISTS amount DECIMAL(10,2) NULL DEFAULT 0.00 COMMENT 'base amount',
// ADD COLUMN IF NOT EXISTS amount_paid DECIMAL(10,2) NULL DEFAULT 0.00 COMMENT 'after discount/tax',
// ADD COLUMN IF NOT EXISTS currency VARCHAR(10) NULL DEFAULT 'SAR',
// ADD COLUMN IF NOT EXISTS payment_gateway VARCHAR(50) NULL,
// ADD COLUMN IF NOT EXISTS coupon_type VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS coupon_amount VARCHAR(255) NULL,
// ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT NULL,
// ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL;
