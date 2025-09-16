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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jobseeker_id'); // Who gave the review

            $table->enum('user_type', ['trainer', 'mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id'); // ID of the person being reviewed

            $table->text('reviews')->nullable();
            $table->unsignedTinyInteger('ratings')->nullable(); // 1-5 rating
            $table->string('trainer_material')->nullable(); // Only for trainers

            $table->timestamps();

            // Indexes
            $table->index(['user_type', 'user_id']);

            // Foreign key constraint for jobseeker_id
            $table->foreign('jobseeker_id')
                ->references('id')
                ->on('jobseekers')
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
        Schema::dropIfExists('reviews');
    }
};
