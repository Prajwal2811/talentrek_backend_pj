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
        Schema::create('recruiter_shortlists', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('recruiter_id')
                ->nullable()
                ->constrained('recruiters')
                ->nullOnDelete();

            $table->foreignId('jobseeker_or_expat_id')
                ->nullable()
                ->constrained('jobseekers_and_expat')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('recruiters_company')
                ->nullOnDelete();

            // Role
            $table->enum('role', ['jobseeker', 'expat'])->nullable();

            // Statuses
            $table->enum('status', ['pending', 'shortlisted', 'rejected'])->nullable();
            $table->enum('interview_request', ['pending', 'requested', 'not_requested'])->nullable();
            $table->enum('interview_status', ['pending', 'completed', 'cancelled'])->nullable();
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->nullable();

            // Interview details
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('interview_result')->nullable();

            // Zoom links
            $table->text('zoom_start_url')->nullable();
            $table->text('zoom_join_url')->nullable();

            $table->timestamps();

            // Indexes for faster queries
            $table->index(['recruiter_id', 'jobseeker_or_expat_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruiter_shortlists');
    }
};
