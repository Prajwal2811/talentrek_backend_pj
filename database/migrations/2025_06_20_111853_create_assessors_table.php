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
        Schema::create('assessors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_email')->unique();
            $table->string('national_id')->unique()->nullable();
            $table->string('phone_code')->nullable();              // e.g., +91
            $table->string('company_phone_number')->nullable();    // e.g., 9876543210
            $table->date('company_instablishment_date')->nullable(); // e.g., 2005-06-15
            $table->string('industry_type')->nullable();           // e.g., Education, IT
            $table->string('company_website')->nullable();  
            $table->string('isSubscribtionBuy')->default('no');       // e.g., https://company.com
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
        Schema::dropIfExists('assessors');
    }
};
