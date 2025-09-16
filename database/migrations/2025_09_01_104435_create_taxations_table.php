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
        Schema::create('taxations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // GST, VAT, etc.
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('rate', 8, 2)->default(0.00);
            $table->enum('user_type', ['mentor', 'trainer', 'assessor', 'coach']); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxations');
    }
};
