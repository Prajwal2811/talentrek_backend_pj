<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned, primary
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type', 50);
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type', 50);
            $table->text('message');
            $table->tinyInteger('type')->comment('1 = Text, 2 = File');
            $table->integer('is_read')->default(0)->nullable();
            $table->timestamps(0); // created_at and updated_at, nullable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

