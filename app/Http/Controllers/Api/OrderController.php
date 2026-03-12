<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Flower;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('flower');

        // Jika user mengirimkan daftar ID (untuk Guest Lacak Pesanan)
        if ($request->has('ids')) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }
        
        // Jika user biasa (bukan admin), hanya tampilkan pesanan mereka
        elseif ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Jika admin mengakses (punya token admin), tampilkan semua jika tidak ada filter
        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        // 1. Find the flower
        $flower = Flower::find($request->flower_id);
        
        // 2. Validate availability and stock
        if (!$flower) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bunga tidak ditemukan!'
            ], 404);
        }

        $quantity = $request->quantity ?? 1;

        if ($flower->stock < $quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, stok bunga ini tidak mencukupi (Tersisa: ' . $flower->stock . ')'
            ], 400);
        }

        // 3. Create the order
        $data = $request->all();
        // Pastikan status default adalah Pending jika tidak dikirim
        if (!isset($data['status'])) {
            $data['status'] = 'Pending';
        }
        
        $order = Order::create($data);

        // 4. Decrement stock
        $flower->decrement('stock', $quantity);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan Berhasil Dibuat!',
            'data' => $order
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diubah',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan Berhasil Dihapus'
        ]);
    }
}
