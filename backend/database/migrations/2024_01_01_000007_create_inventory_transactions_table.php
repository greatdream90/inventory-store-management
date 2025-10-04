<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ผู้ทำรายการ
            $table->enum('type', ['in', 'out', 'adjustment']); // เข้า, ออก, ปรับยอด
            $table->integer('quantity'); // + หรือ - ขึ้นอยู่กับ type
            $table->integer('quantity_before'); // จำนวนก่อนเปลี่ยน
            $table->integer('quantity_after'); // จำนวนหลังเปลี่ยน
            $table->decimal('unit_cost', 10, 2)->nullable(); // ต้นทุนต่อหน่วย
            $table->string('reference_type')->nullable(); // sale, purchase, adjustment
            $table->unsignedBigInteger('reference_id')->nullable(); // อ้างอิงไป sale_id, purchase_id
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
};