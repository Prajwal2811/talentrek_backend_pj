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
        Schema::create('jobseeker_assessment_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('question_id');
            $table->string('selected_answer');
            $table->string('correct_answer');
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('training_id')->references('id')->on('training_materials')->onDelete('cascade');
            $table->foreign('assessment_id')->references('id')->on('trainer_assessments')->onDelete('cascade');
            $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('assessment_options')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_assessment_data');
    }
};
