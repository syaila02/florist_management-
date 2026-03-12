<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flowers', function (Blueprint $table) {
            $table->id();
            // Menambahkan kategori baru untuk Kertas, Boneka, dll.
            $table->enum('category', [
                'Fresh Flower', 
                'Pipe Cleaner Flower', 
                'Artificial Flower', 
                'Wrapping Paper', 
                'Accessory', 
                'Add-on'
            ]);
            $table->string('flower_name', 100);
            $table->enum('product_type', ['Satuan', 'Buket', 'Komponen'])->default('Satuan');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flowers');
    }
};
