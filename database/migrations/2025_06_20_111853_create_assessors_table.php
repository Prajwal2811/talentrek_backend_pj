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
            $table->string('name')->nullable();                
            $table->string('email')->unique();               
            $table->string('phone_code')->nullable();         
            $table->string('phone_number')->nullable();       
            $table->date('date_of_birth')->nullable();       
            $table->string('city')->nullable();               
            $table->string('password')->nullable();        
            $table->string('pass')->nullable();          
            $table->string('otp')->nullable();                
            $table->string('national_id')->unique()->nullable();
            $table->string('status')->default('active');          
            $table->string('admin_status')->nullable();  
            $table->text('about_assessor')->nullable();       
            $table->string('isSubscribtionBuy')->default('no'); 
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
