<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Review::with('order.flower')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        // Cek apakah sudah pernah direview
        if (Review::where('order_id', $request->order_id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan ini sudah diberi ulasan.'
            ], 422);
        }

        $review = Review::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil dikirim',
            'data' => $review
        ], 201);
    }
}
