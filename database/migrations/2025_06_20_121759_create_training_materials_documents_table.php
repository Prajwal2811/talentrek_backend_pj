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
       Schema::create('training_materials_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trainer_id');                 // FK to trainers
            $table->unsignedBigInteger('training_material_id');       // FK to training_materials

            $table->string('training_title')->nullable();             // Title for the document
            $table->text('description')->nullable();                  // Optional description
            $table->string('file_path')->nullable();                  // Path to stored document
            $table->string('file_name')->nullable();                  // Original file name

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('trainer_id')
                ->references('id')
                ->on('trainers')
                ->onDelete('cascade');

            $table->foreign('training_material_id')
                ->references('id')
                ->on('training_materials')
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
        Schema::dropIfExists('training_materials_documents');
    }
};
