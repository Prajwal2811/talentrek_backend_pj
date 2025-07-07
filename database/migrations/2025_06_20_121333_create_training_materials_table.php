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
       Schema::create('training_materials', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trainer_id');               // FK to trainers table
            $table->string('training_type')->nullable();                        // e.g., Online, Offline, Hybrid
            $table->string('training_title')->nullable();
            $table->string('training_sub_title')->nullable();
            $table->text('training_descriptions')->nullable();
            $table->string('training_category')->nullable();        // e.g., Technical, Soft Skills
            $table->decimal('training_price', 10, 2)->default(0);   // e.g., 299.99
            $table->string('thumbnail_file_path')->nullable();      // Path to stored image
            $table->string('thumbnail_file_name')->nullable();      // Original filename
            $table->text('training_objective')->nullable();         // Objectives
            $table->string('session_type')->nullable();             // e.g., Live, Pre-recorded
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('trainer_id')
                  ->references('id')
                  ->on('trainers')
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
        Schema::dropIfExists('training_materials');
    }
};
