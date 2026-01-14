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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type', [
                'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
            ]);
            $table->string('title'); // Silver, Gold, Platinum
            $table->decimal('price', 8, 2); // AED 49.00 etc.
            $table->text('description')->nullable(); // Paragraph below the price
            $table->json('features')->nullable(); // Feature list: ["Lorem ipsum", ...]
            $table->integer('duration_days')->nullable(); // Duration in days (optional)
            $table->boolean('is_active')->default(true); // Active/inactive subscription
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
        Schema::dropIfExists('subscriptions');
    }
};
