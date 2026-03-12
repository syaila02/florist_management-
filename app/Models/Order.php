<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flower_id',
        'order_type',
        'paper_name',
        'accessory_name',
        'quantity',
        'selected_color',
        'total_price',
        'payment_method',
        'status'
    ];

    public function flower()
    {
        return $this->belongsTo(Flower::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
