<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AssetAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with('kategori')->get();
        $categories = Category::all();
        return view('admin.assets', compact('assets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_asset' => 'required|unique:assets',
            'nama_asset' => 'required',
            'category_id' => 'required|exists:categories,id', // VALIDASI CATEGORY
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:1',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            // ambil nama asli file
            $filename = $request->file('gambar')->getClientOriginalName();

            // simpan di storage/app/public/images dengan nama asli
            $path = $request->file('gambar')->storeAs('images', $filename, 'public');
            // hasil $path = "images/cctv.jpg"
        }

        Asset::create([
            'kode_asset' => $request->kode_asset,
            'nama_asset' => $request->nama_asset,
            'category_id' => $request->category_id, // TAMBAHKAN INI
            'gambar' => $path,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
        ]);

        return redirect('/admin/assets')->with('success', 'Asset berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        return view('admin.editAsset', compact('asset', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'kode_asset' => 'required|unique:assets,kode_asset,' . $asset->id,
            'nama_asset' => 'required',
            'category_id' => 'required|exists:categories,id', // VALIDASI CATEGORY
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:1',
        ]);

        $path = $asset->gambar; // default tetap gambar lama

        if ($request->hasFile('gambar')) {
            // ambil nama asli file
            $filename = $request->file('gambar')->getClientOriginalName();

            // simpan di storage/app/public/images dengan nama asli
            $path = $request->file('gambar')->storeAs('images', $filename, 'public');
        }

        $asset->update([
            'kode_asset' => $request->kode_asset,
            'nama_asset' => $request->nama_asset,
            'category_id' => $request->category_id, // TAMBAHKAN INI
            'gambar' => $path,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.assets.index')->with('success', 'Asset berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        // hapus file gambar jika ada
        if ($asset->gambar && Storage::disk('public')->exists($asset->gambar)) {
            Storage::disk('public')->delete($asset->gambar);
        }

        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success', 'Asset berhasil dihapus!');
    }
}
