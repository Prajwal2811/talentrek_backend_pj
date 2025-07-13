<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_materials', function (Blueprint $table) {
            $table->id();

            // Foreign key column
            $table->foreignId('trainer_id')
                ->constrained('trainers')
                ->onDelete('cascade'); // Enforces FK constraint

            // Training details
            $table->string('training_type')->nullable();                 // Online, Offline, Hybrid
            $table->string('training_title')->nullable();
            $table->string('training_level')->nullable();
            $table->string('training_sub_title')->nullable();
            $table->text('training_descriptions')->nullable();

            $table->string('training_category')->nullable();            // Technical, Soft Skills, etc.
            $table->decimal('training_price', 10, 2)->default(0);       // e.g. 299.99
            $table->decimal('training_offer_price', 10, 2)->default(0);       // e.g. 299.99

            // Thumbnail
            $table->string('thumbnail_file_path')->nullable();          // Storage path
            $table->string('thumbnail_file_name')->nullable();          // Original name



            // Objective and status
            $table->text('training_objective')->nullable();
            $table->string('session_type')->nullable();                 // Live, Pre-recorded
            $table->string('admin_status')->nullable();                 // pending, approved, etc.
            $table->text('rejection_reason')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_materials');
    }
};
