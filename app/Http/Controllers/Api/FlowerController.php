<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flower;
use Illuminate\Http\Request;

class FlowerController extends Controller
{
    public function index(Request $request)
    {
        $query = Flower::query();

        if ($request->has('search')) {
            $query->where('flower_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $sortBy = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');

        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy($sortBy, $order)->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        // Decode color JSON string if it's sent as a string (from FormData)
        if ($request->has('color') && is_string($request->color)) {
            $data['color'] = json_decode($request->color, true);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }
        $flower = Flower::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Bunga berhasil ditambahkan',
            'data' => $flower
        ], 201);
    }

    public function show($id)
    {
        $flower = Flower::find($id);
        if (!$flower) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bunga tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $flower
        ]);
    }

    public function update(Request $request, $id)
    {
        $flower = Flower::find($id);
        if (!$flower) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bunga tidak ditemukan'
            ], 404);
        }

        $data = $request->all();

        // Decode color JSON string if it's sent as a string (from FormData)
        if ($request->has('color') && is_string($request->color)) {
            $data['color'] = json_decode($request->color, true);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $flower->update($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Bunga berhasil diperbarui',
            'data' => $flower
        ]);
    }


    public function destroy($id)
    {
        $flower = Flower::find($id);
        if (!$flower) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bunga tidak ditemukan'
            ], 404);
        }
        $flower->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Bunga berhasil dihapus'
        ]);
    }
}
