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
        Schema::create('training_experience', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', [
                'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
            ]);

            $table->text('training_experience')->nullable();   // Description of experience
            $table->text('training_skills')->nullable();       // Comma-separated skills
            $table->string('website_link')->nullable();        // Personal or company site
            $table->string('portfolio_link')->nullable();      // e.g., GitHub, Behance

            $table->timestamps();

            // Index for fast lookups
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
        Schema::dropIfExists('training_experience');
    }
};
