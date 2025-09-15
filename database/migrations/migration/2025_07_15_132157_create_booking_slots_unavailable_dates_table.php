<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSlotsUnavailableDatesTable extends Migration
{
    public function up()
    {
        Schema::create('booking_slots_unavailable_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_slot_id');
            $table->date('unavailable_date');
            $table->timestamps();

            // Foreign key constraint (optional but recommended)
            // $table->foreign('booking_slot_id')
            //       ->references('id')
            //       ->on('booking_slots')
            //       ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_slots_unavailable_dates');
    }
}
