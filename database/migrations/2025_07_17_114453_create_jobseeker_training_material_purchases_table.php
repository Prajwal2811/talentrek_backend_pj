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
        Schema::create('jobseeker_training_material_purchases', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('jobseeker_id')->constrained('jobseekers')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('set null')->nullable(false);
            $table->foreignId('material_id')->constrained('training_materials')->onDelete('set null')->nullable(false);
            
            // Nullable enum types
            $table->enum('training_type', ['online', 'classroom', 'recorded'])->nullable();
            $table->enum('session_type', ['online', 'classroom'])->nullable();

            // Nullable batch
            $table->foreignId('batch_id')->nullable()->constrained('training_batches')->onDelete('set null');

            // Purchase for type
            $table->enum('purchase_for', ['individual', 'team']);

            // Payment relation
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');

            // Status
            $table->string('batchStatus')->nullable();

            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending')->change();
            
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
        Schema::dropIfExists('jobseeker_training_material_purchases');
    }
};
