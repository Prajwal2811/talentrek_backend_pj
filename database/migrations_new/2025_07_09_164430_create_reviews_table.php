<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->unsignedBigInteger('jobseeker_or_expat_id'); // Who gave the review
            $table->enum('role', ['jobseeker', 'expat'])->nullable();

            $table->enum('user_type', ['trainer', 'mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id'); // ID of the person being reviewed

            $table->text('reviews')->nullable();
            $table->unsignedTinyInteger('ratings')->nullable(); // 1-5 rating
            $table->string('trainer_material')->nullable(); // Only for trainers

            $table->timestamps();

            // Indexes
            $table->index(['user_type', 'user_id']);

            // Foreign key constraint with correct table
            $table->foreign('jobseeker_or_expat_id', 'fk_reviews_jobseeker')
                  ->references('id')
                  ->on('jobseekers_and_exapt')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
