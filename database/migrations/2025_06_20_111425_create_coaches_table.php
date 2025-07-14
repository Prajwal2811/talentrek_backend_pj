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
       Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('national_id')->unique()->nullable();
            $table->string('phone_code')->nullable();       // e.g., +91, +1
            $table->string('phone_number')->nullable();     // e.g., 9876543210
            $table->date('date_of_birth')->nullable();      // e.g., 1990-05-22
            $table->string('city')->nullable();     
            $table->string('isSubscribtionBuy')->default('no');
            $table->text('about_coach')->nullable();        // e.g., Mumbai, Bangalore
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
        Schema::dropIfExists('coaches');
    }
};
