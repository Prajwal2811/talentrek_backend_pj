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
        $table->unsignedBigInteger('company_id')->nullable(); // FK to recruiters_company
        $table->string('name')->nullable();
        $table->string('email')->unique()->nullable();
        $table->string('status')->nullable(); 
        $table->string('inactive_reason')->nullable(); 
        $table->string('admin_status')->nullable(); 
        $table->string('rejection_reason')->nullable(); 
        $table->timestamps();

        $table->foreign('company_id')
            ->references('id')->on('recruiters_company')
            ->onDelete('cascade');

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
