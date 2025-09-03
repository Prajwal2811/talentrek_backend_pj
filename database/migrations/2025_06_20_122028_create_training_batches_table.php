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
        Schema::create('training_batches', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trainer_id');                // FK to trainers table
            $table->unsignedBigInteger('training_material_id');      // FK to training_materials table

            $table->string('batch_no')->nullable();                  // Unique or label for batch
            $table->date('start_date')->nullable();                  // e.g., 2025-07-01
            $table->date('end_date')->nullable();                  // e.g., 2025-07-01
            $table->time('start_timing')->nullable();                // e.g., 10:00:00
            $table->time('end_timing')->nullable();                  // e.g., 12:00:00
            $table->string('duration')->nullable();                  // e.g., "2 hours", "4 weeks"
            $table->string('strength')->nullable();                  // e.g., "50", "100"
            $table->string('location')->nullable();                  // e.g., "Online", "Offline"
            $table->string('course_url')->nullable();                // e.g., "Online", "Offline"
            $table->string('status')->nullable();                    // e.g., "Active", "Completed"
            $table->text('zoom_start_url')->nullable();
            $table->text('zoom_join_url')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('trainer_id')
                ->references('id')
                ->on('trainers')
                ->onDelete('cascade');

            $table->foreign('training_material_id')
                ->references('id')
                ->on('training_materials')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_batches');
    }
};
