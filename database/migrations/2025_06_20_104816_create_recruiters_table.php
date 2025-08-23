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

       Schema::create('recruiters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('national_id')->unique()->nullable();
            $table->string('status')->default('active');
            $table->string('password')->nullable();
            $table->string('pass')->nullable();
            $table->integer('otp')->nullable();
            $table->string('role')->nullable();
            $table->string('recruiter_of')->nullable();
            $table->string('company_id')->nullable();
            $table->string('is_registered')->default('0');
            $table->string('inactive_reason')->nullable(); 
            $table->string('admin_status')->nullable(); 
            $table->string('rejection_reason')->nullable(); 
            $table->timestamps();
        });
    }

    /**w
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruiters');
    }
};
