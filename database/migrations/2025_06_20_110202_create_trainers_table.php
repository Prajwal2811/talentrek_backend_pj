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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('national_id')->unique()->nullable();
            $table->string('phone_code')->nullable();       // e.g., +91, +1
            $table->string('phone_number')->nullable();     // e.g., 9876543210
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();         // e.g., city or state
            $table->string('state')->nullable();         // e.g., city or state
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->text('country')->nullable();
            $table->string('password')->nullable(); 
            $table->string('pass')->nullable(); 
            $table->string('otp')->nullable();            
            $table->string('status')->nullable();            
            $table->string('admin_status')->nullable();            
            $table->text('inactive_reason')->nullable(); // Removed ->after('status')
            $table->text('rejection_reason')->nullable(); // Removed ->after('status') 
            $table->string('isSubscribtionBuy')->default('no');           // e.g., Mumbai, Delhi
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
        Schema::dropIfExists('trainers');
    }
};
