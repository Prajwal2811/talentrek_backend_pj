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
        Schema::create('trainer_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainer_id');
            $table->string('assessment_title');
            $table->text('assessment_description')->nullable();
            $table->string('assessment_level')->nullable(); // e.g. beginner, intermediate, expert
            $table->unsignedInteger('total_questions');
            $table->unsignedInteger('passing_questions');
            $table->string('time_per_question')->nullable(); // e.g. beginner, intermediate, expert
            $table->string('passing_percentage')->nullable(); // e.g. beginner, intermediate, expert
            $table->unsignedBigInteger('material_id')->nullable(); // e.g. beginner, intermediate, expert
            $table->timestamps();

            // Optional: Foreign keys if you have related tables
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('training_materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainer_assessments');
    }
};
