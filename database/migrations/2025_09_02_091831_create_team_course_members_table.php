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
    public function up(): void
    {
        Schema::create('team_course_members', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('main_jobseeker_id'); // main jobseeker
            $table->unsignedBigInteger('jobseeker_id'); // main jobseeker
            $table->unsignedBigInteger('trainer_id')->nullable(); // trainer
            $table->unsignedBigInteger('training_material_purchases_id'); // purchase relation
            $table->unsignedBigInteger('material_id')->nullable(); // training material
            $table->string('training_type')->nullable(); // e.g., online/offline
            $table->string('session_type')->nullable(); // e.g., group/individual
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('track_id')->nullable();
            $table->string('email');
            $table->timestamps();

            // Foreign key relation with purchases table
            $table->foreign('training_material_purchases_id', 'team_course_members_purchase_fk')
                            ->references('id')
                            ->on('jobseeker_training_material_purchases')
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
        Schema::dropIfExists('team_course_members');
    }
};
