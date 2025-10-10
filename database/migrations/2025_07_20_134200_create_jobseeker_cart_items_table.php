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

            // Ownership
            $table->unsignedBigInteger('jobseeker_id')->index();
            $table->unsignedBigInteger('trainer_id')->index();

            // Material / Training Info
            $table->string('material_type')->nullable(); // e.g., 'online', 'offline', 'hybrid'
            $table->unsignedBigInteger('material_id')->index();

            // Selected Batch
            $table->unsignedBigInteger('batch_id')->nullable()->index();

            // Financial Info
            $table->decimal('price', 10, 2)->nullable(); // Latest price from material
            $table->integer('available_seats')->nullable(); // Updated from batch
            $table->string('batch_status')->default('active'); // 'active', 'upcoming', 'full', 'expired', 'invalid'

            // Cart Status
            $table->string('status')->nullable(); // 'pending', 'purchased', 'removed'

            $table->timestamps();

            // Foreign Keys (optional — uncomment when related tables exist)
            // $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade');
            // $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            // $table->foreign('material_id')->references('id')->on('training_materials')->onDelete('cascade');
            // $table->foreign('batch_id')->references('id')->on('training_batches')->onDelete('set null');
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
