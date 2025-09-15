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
       Schema::create('education_details', function (Blueprint $table) {
            $table->id();

            // Polymorphic fields (required)
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', [
                'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
            ]);

            // Education fields (nullable)
            $table->string('high_education')->nullable();   // e.g., Bachelor's, Master's
            $table->string('field_of_study')->nullable();   // e.g., Computer Science
            $table->string('institution')->nullable();      // e.g., University Name
            $table->year('graduate_year')->nullable();      // e.g., 2022

            $table->timestamps();

            // Index for lookup performance
            $table->index(['user_id', 'user_type']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education_details');
    }
};
