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
            $table->unsignedBigInteger('recruiter_id')->nullable(); 
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_city')->nullable();
            $table->text('company_address')->nullable();
            $table->string('business_email')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('company_phone_number')->nullable(); 
            $table->string('password')->nullable();
            $table->string('pass')->nullable();
            $table->integer('otp')->nullable();
            $table->string('no_of_employee')->nullable();
            $table->string('industry_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('status')->default('active');
            $table->string('admin_status')->nullable();
            $table->string('inactive_reason')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('is_registered')->default('0');  
            $table->string('isSubscribtionBuy')->default('no');
            $table->timestamps();

                // Cascade delete recruiters when company is deleted
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
