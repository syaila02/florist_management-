<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flower_id')->constrained('flowers')->onDelete('cascade');
            $table->string('order_type'); // Satuan / Buket
            $table->string('paper_name')->nullable();
            $table->string('accessory_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('payment_method')->nullable();
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('Pending'); // Pending, Proses, Selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
