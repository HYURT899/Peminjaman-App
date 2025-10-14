<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua kategori
        $categories = Category::all();

        // Ambil kategori yang dipilih dari URL (misal ?category=1)
        $selectedCategory = $request->get('category');

        // Query asset beserta relasi kategori
        $assetsQuery = Asset::with('kategori');

        // Jika kategori dipilih, filter berdasarkan category_id
        if ($selectedCategory) {
            $assetsQuery->where('category_id', $selectedCategory);
        }

        // Pagination (8 per halaman)
        $assets = $assetsQuery->latest()->paginate(8);

        // Kirim data ke view
        return view('public.assets.index', compact('assets', 'categories', 'selectedCategory'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asset = Asset::findOrFail($id);
        return view('public.assets.show', compact('asset'));
    }
}
