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
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id();

            $table->enum('user_type', ['mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id');

            $table->enum('slot_mode', ['online', 'offline'])->default('online'); // Adjust if needed
            $table->time('start_time');
            $table->time('end_time');

            $table->json('unavailable_dates')->nullable(); // For storing an array of unavailable dates
            $table->boolean('is_available')->nullable();
            $table->boolean('is_booked')->nullable();


            
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
        Schema::dropIfExists('booking_slots');
    }
};
