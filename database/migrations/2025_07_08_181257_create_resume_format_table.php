<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resumes_format', function (Blueprint $table) {
            $table->id();
            $table->string('resume')->nullable(); // PDF/DOC/DOCX file path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes_format');
    }
};
