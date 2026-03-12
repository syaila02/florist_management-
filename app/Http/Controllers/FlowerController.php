<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use Illuminate\Http\Request;

class FlowerController extends Controller
{
    public function index(Request $request)
    {
        $query = Flower::query();

        // Fitur Pencarian
        if ($request->has('search')) {
            $query->where('flower_name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // FITUR 3: SORTING (Urutkan Data)
        $sortBy = $request->get('sort', 'flower_name'); // Default urut nama
        $order = $request->get('order', 'asc'); // Default A-Z
        
        $flowers = $query->orderBy($sortBy, $order)->get();

        return view('katalog', compact('flowers'));
    }
}
