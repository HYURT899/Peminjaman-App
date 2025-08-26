<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asset = Asset::all();

        return view('admin.assets', compact('asset'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_asset' => 'required|unique:assets',
            'nama_asset' => 'required',
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
            'gambar' => $path, // simpan path relatif
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
        ]);

        return redirect()->back()->with('success', 'Asset berhasil ditambahkan!');
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
    public function edit($id)
    {
        // 
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
        'gambar' => $path,
        'deskripsi' => $request->deskripsi,
        'stok' => $request->stok,
    ]);

    return redirect()->back()->with('success', 'Asset berhasil diperbarui!');
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
