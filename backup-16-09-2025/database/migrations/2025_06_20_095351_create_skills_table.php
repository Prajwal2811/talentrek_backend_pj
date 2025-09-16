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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobseeker_id');
            $table->text('skills')->nullable();           // e.g., "PHP, Laravel, JavaScript"
            $table->text('interest')->nullable();         // e.g., "UI/UX Design, Backend Development"
            $table->string('job_category')->nullable();   // e.g., "IT", "Marketing"
            $table->string('website_link')->nullable();   // e.g., personal site or blog
            $table->string('portfolio_link')->nullable(); // e.g., Behance, GitHub, Dribbble
            $table->timestamps();

            // Foreign key constraint
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
        Schema::dropIfExists('skills');
    }
};
