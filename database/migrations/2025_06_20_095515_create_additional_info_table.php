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
       Schema::create('additional_info', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation: user_id + user_type
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', [
                'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
            ]);

            $table->string('doc_type')->nullable();           // e.g., Resume, Certificate, ID Proof
            $table->string('document_name')->nullable();      // Display name or original name
            $table->string('document_path')->nullable();      // Path or URL to the uploaded file

            $table->timestamps();

            // Optional: Index for better performance
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
        Schema::dropIfExists('additional_info');
    }
};
