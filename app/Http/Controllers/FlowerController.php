<?php

namespace App\Http\Controllers;

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

        $sortBy = $request->get('sort', 'flower_name'); 
        $order = $request->get('order', 'asc'); 
        
        $flowers = $query->orderBy($sortBy, $order)->get();

        return view('katalog', compact('flowers'));
    }
}
