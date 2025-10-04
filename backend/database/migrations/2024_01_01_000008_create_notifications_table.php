<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'error', 'success'])->default('info');
            $table->enum('category', ['low_stock', 'sales', 'system', 'user'])->default('system');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // ถ้าเป็น null = แจ้งเตือนทั่วไป
            $table->json('data')->nullable(); // ข้อมูลเพิ่มเติม
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};