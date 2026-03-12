<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    use HasFactory;

    protected $table = 'flowers';
    public $timestamps = false;

    protected $fillable = [
        'category',
        'flower_name',
        'color',
        'product_type',
        'target_gender',
        'price',
        'stock',
        'image' // Kolom baru untuk nama file gambar
    ];

    protected $casts = [
        'color' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}
