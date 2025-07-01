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
         Schema::create('jobseekers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('assigned_admin')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('phone_code')->nullable();       // e.g., +91, +1
            $table->string('phone_number')->nullable();     // e.g., 9876543210
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();         // e.g., city or state
            $table->text('address')->nullable();
            $table->string('password')->nullable();
            $table->string('pass')->nullable();
            $table->string('role')->nullable();             // optional role field
            $table->string('otp')->nullable();            
            $table->string('status')->nullable();            
            $table->string('admin_status')->nullable();            
            $table->text('inactive_reason')->nullable(); // Removed ->after('status')
            $table->text('rejection_reason')->nullable(); // Removed ->after('status')
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
        Schema::dropIfExists('jobseekers');
    }
};
