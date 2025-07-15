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
       Schema::create('jobseeker_saved_booking_session', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobseeker_id');
            $table->enum('user_type', ['mentor', 'coach', 'assessor']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_slot_id');
            $table->string('slot_mode')->nullable(); // For storing the mode of the slot (e.g., online, offline)
            $table->date('slot_date');
            $table->string('slot_time');
            $table->string('status')->default('pending'); // example values: pending, confirmed, cancelled
            $table->string('admin_status')->nullable(); // e.g., approved, rejected, pending
            $table->boolean('is_postpone')->default(false);
            $table->date('slot_date_after_postpone')->nullable(); // Date after postponing, if applicable
            $table->string('slot_time_after_postpone')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('rescheduled_at')->nullable();
            $table->timestamps();

            // Optional: Add foreign keys if tables exist
            // $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade');
            // $table->foreign('booking_slot_id')->references('id')->on('booking_slots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_saved_booking_session');
    }
};
