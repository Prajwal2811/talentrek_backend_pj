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
            $table->string('jobseeker_id')->nullable();
            $table->string('trainer_id')->nullable();
            $table->string('material_id')->nullable();
            
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

            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending')->change();
            
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
