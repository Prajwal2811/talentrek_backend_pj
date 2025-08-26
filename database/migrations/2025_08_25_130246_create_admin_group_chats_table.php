<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_group_chats', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned, primary, auto_increment
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('sender_type', 50);
            $table->string('receiver_type', 50);
            $table->text('message');
            $table->tinyInteger('type')->comment('1=Text, 2=File');
            $table->integer('is_read')->default(0)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_group_chats');
    }
};


