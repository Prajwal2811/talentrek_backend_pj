<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
        $table->id();
        $table->enum('user_type', [
            'jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'
        ]);
        $table->year('year')->default(date('Y')); // Removed ->after('user_type')
        $table->string('title'); // Silver, Gold, Platinum
        $table->decimal('price', 8, 2); // AED 49.00 etc.
        $table->text('description')->nullable(); // Paragraph below the price
        $table->json('features')->nullable(); // Feature list: ["Lorem ipsum", ...]
        $table->integer('duration_days')->nullable(); // Duration in days (optional)
        $table->boolean('is_active')->default(true); // Active/inactive subscription
        $table->string('slug');
        $table->timestamps();
    });


    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
}
