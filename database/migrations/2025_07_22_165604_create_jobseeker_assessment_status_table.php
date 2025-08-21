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
        Schema::create('jobseeker_assessment_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobseeker_id');
            $table->unsignedBigInteger('assessment_id');
            $table->boolean('submitted')->default(false);
            $table->timestamps();

              $table->unique(['jobseeker_id', 'assessment_id'], 'js_assess_status_unique');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_assessment_status');
    }
};
