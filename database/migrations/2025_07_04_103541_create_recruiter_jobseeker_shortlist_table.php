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
        Schema::create('recruiter_jobseeker_shortlist', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruiter_id')
                ->nullable()
                ->constrained('recruiters')
                ->nullOnDelete();

            $table->foreignId('jobseeker_id')
                ->nullable()
                ->constrained('jobseekers')
                ->nullOnDelete();


            $table->foreignId('company_id')
                ->nullable()
                ->constrained('recruiters_company')
                ->nullOnDelete();


            $table->string('status')->nullable();
            $table->string('interview_request')->nullable();
            $table->string('admin_status')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('interview_url')->nullable();
            
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruiter_jobseeker_shortlist');
    }
};
