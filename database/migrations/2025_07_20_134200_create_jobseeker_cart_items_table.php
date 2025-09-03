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
        Schema::create('jobseeker_cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('material_id');
            $table->string('status')->default('pending'); // e.g., 'pending', 'purchased'
            $table->timestamps();

            // Foreign Keys (Optional - uncomment if relationships exist)
            // $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade');
            // $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            // $table->foreign('material_id')->references('id')->on('training_materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_cart_items');
    }
};
