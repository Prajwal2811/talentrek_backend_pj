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
        Schema::create('work_experience', function (Blueprint $table) {
            $table->id();

            // Polymorphic user reference
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', [
                'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
            ]);

            // Work experience fields (nullable as needed)
            $table->string('job_role')->nullable();         // e.g., Software Developer
            $table->string('organization')->nullable();     // e.g., TCS, Infosys
            $table->date('starts_from')->nullable();        // Start date
            $table->date('end_to')->nullable();             // End date (nullable if currently employed)

            $table->timestamps();

            // Index for polymorphic relationship performance
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
        Schema::dropIfExists('work_experience');
    }
};
