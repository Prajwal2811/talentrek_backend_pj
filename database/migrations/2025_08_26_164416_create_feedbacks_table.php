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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic sender (could be recruiter, jobseeker, admin, etc.)
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); 
            
            // Polymorphic receiver (feedback to someone)
            $table->unsignedBigInteger('to_id');
            $table->string('to_type');
            
            $table->text('feedback_message');
            
            $table->timestamps();

            // Optional indexes for performance
            $table->index(['sender_id', 'sender_type']);
            $table->index(['to_id', 'to_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
};
