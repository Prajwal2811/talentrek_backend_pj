<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_course_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->unsignedBigInteger('main_jobseeker_or_expat_id'); // main jobseeker
            $table->unsignedBigInteger('jobseeker_or_expat_id'); 
            $table->enum('role', ['jobseeker', 'expat'])->nullable();
            $table->unsignedBigInteger('trainer_id')->nullable(); 
            $table->unsignedBigInteger('training_material_purchases_id'); 
            $table->unsignedBigInteger('material_id')->nullable(); 
            $table->string('training_type')->nullable(); 
            $table->string('session_type')->nullable(); 
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

    public function down()
    {
        Schema::dropIfExists('team_course_members');
    }
};
