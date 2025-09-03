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
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('material_id');
            $table->enum('training_type', ['online', 'classroom', 'recorded'])->nullable();
            $table->enum('session_type', ['online', 'classroom'])->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->enum('purchase_for', ['individual', 'team']);
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->timestamps();

            // Foreign key constraints (optional, adjust table names as per your DB structure)
            // $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade');
            // $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');
            // $table->foreign('material_id')->references('id')->on('training_materials')->onDelete('set null');
            // $table->foreign('batch_id')->references('id')->on('training_batches')->onDelete('set null');
            // $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
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
