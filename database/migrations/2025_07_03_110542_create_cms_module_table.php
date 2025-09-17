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
        Schema::create('cms_module', function (Blueprint $table) {
            $table->id();

            // Default (English or primary language)
            $table->string('section')->nullable();
            $table->string('slug')->nullable();
            $table->string('heading')->nullable();
            $table->text('description')->nullable();

            // Arabic (prefixed with ar_)
            $table->string('ar_section')->nullable();
            $table->string('ar_heading')->nullable();
            $table->text('ar_description')->nullable();

            // File fields
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();

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
        Schema::dropIfExists('cms_module');
    }
};
