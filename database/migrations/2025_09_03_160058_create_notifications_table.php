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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type', 50);
            $table->unsignedBigInteger('receiver_id');
            $table->string('user_type', 50)->nullable();
            $table->text('message');
            $table->tinyInteger('type')->nullable()->comment('1 = Text, 2 = File');
            $table->integer('is_read')->default(0);
            $table->integer('is_read_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
