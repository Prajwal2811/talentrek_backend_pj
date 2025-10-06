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
        Schema::create('jobseeker_and_expat_assessment_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('jobseeker_or_expat_id');
            $table->enum('role', ['jobseeker', 'expat'])->nullable();

            $table->unsignedBigInteger('question_id');
            $table->string('selected_answer');
            $table->string('correct_answer');

            $table->timestamps();

            // Shortened foreign key names
            $table->foreign('trainer_id', 'fk_jad_trainer')
                  ->references('id')->on('trainers')->onDelete('cascade');

            $table->foreign('training_id', 'fk_jad_training')
                  ->references('id')->on('training_materials')->onDelete('cascade');

            $table->foreign('assessment_id', 'fk_jad_assessment')
                  ->references('id')->on('trainer_assessments')->onDelete('cascade');

            $table->foreign('jobseeker_or_expat_id', 'fk_jad_jobseeker')
                  ->references('id')->on('jobseekers_and_exapt')->onDelete('cascade');

            $table->foreign('question_id', 'fk_jad_question')
                  ->references('id')->on('assessment_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_and_expat_assessment_data');
    }
};
