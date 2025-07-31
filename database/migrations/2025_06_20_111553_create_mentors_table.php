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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('national_id')->unique()->nullable();
            $table->string('phone_code')->nullable();       // e.g., +91, +1
            $table->string('phone_number')->nullable();     // e.g., 9876543210
            $table->date('date_of_birth')->nullable();      // e.g., 1985-12-20
            $table->string('city')->nullable();         // e.g., city or state
            $table->string('state')->nullable();         // e.g., city or state
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->text('country')->nullable();
            $table->string('password')->nullable(); 
            $table->string('pass')->nullable();    
            $table->string('otp')->nullable();            
            $table->string('status')->default('active');          
            $table->string('admin_status')->nullable();            
            $table->string('inactive_reason')->nullable(); // Removed ->after('status')
            $table->string('rejection_reason')->nullable(); // Removed ->after('status')
            $table->string('shortlist')->nullable(); // Removed ->after('status')
            $table->string('admin_recruiter_status')->nullable(); // Removed ->after('status')
            $table->string('google_id')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->text('about_mentor')->nullable();
            $table->string('isSubscribtionBuy')->default('no');        // e.g., New York, Delhi
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
        Schema::dropIfExists('mentors');
    }
};
