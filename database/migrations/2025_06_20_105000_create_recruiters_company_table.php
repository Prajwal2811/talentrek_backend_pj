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
       Schema::create('recruiters_company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiter_id');             // FK to recruiters table
            $table->string('company_name');                         // Required
            $table->string('company_website')->nullable();
            $table->string('company_city')->nullable();
            $table->text('company_address')->nullable();
            $table->string('business_email')->nullable();
            $table->string('phone_code')->nullable();               // e.g., +91
            $table->string('company_phone_number')->nullable();     // e.g., 9876543210
            $table->string('no_of_employee')->nullable();           // e.g., "11-50", "100+"
            $table->string('industry_type')->nullable();            // e.g., IT, Finance
            $table->string('registration_number')->nullable();      // Optional registration or GST number
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('recruiter_id')
                ->references('id')
                ->on('recruiters')
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
        Schema::dropIfExists('recruiters_company');
    }
};
