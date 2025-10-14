<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\Category;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\ErrorCorrectionLevel;

class AssetAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with('kategori')->get();
        $categories = Category::all();
        return view('admin.assets.assets', compact('assets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.assets.createAsset', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'kode_asset' => 'required|unique:assets',
            'nama_asset' => 'required',
            'category_id' => 'required|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $filename = $request->file('gambar')->getClientOriginalName();
            $path = $request->file('gambar')->storeAs('images', $filename, 'public');
        }

        // 1. Simpan asset
        $asset = Asset::create([
            'kode_asset' => $request->kode_asset,
            'nama_asset' => $request->nama_asset,
            'category_id' => $request->category_id,
            'gambar' => $path,
            'deskripsi' => $request->deskripsi,
            'qr_code' => null,
        ]);

        // 2. Generate QR Code
        try {
            $url = url('/daftar_asset/' . $asset->id);

            // Check tempat penyimpanan kalau ga ada bikin baru
            if (!Storage::disk('public')->exists('qrcodes')) {
                Storage::disk('public')->makeDirectory('qrcodes');
            }

            $qrCodeFileName = "qrcodes/asset-{$asset->kode_asset}.png";

            $writer = new PngWriter();

            // Buat objek QrCode
            $qrCode = new QrCode(data: $url, encoding: new Encoding('UTF-8'), 
                errorCorrectionLevel: ErrorCorrectionLevel::High, size: 300, margin: 10, 
                roundBlockSizeMode: RoundBlockSizeMode::Margin, 
                foregroundColor: new Color(0, 0, 0), 
                backgroundColor: new Color(255, 255, 255)
            );

            $result = $writer->write($qrCode);

            // Simpan ke storage public
            $result->saveToFile(storage_path("app/public/{$qrCodeFileName}"));

            if (file_exists(storage_path('app/public/' . $qrCodeFileName))) {
                Log::info('QR Code berhasil dibuat: ' . $qrCodeFileName);
            } else {
                Log::error('QR Code gagal disimpan!');
            }

            $asset->update(['qr_code' => $qrCodeFileName]);
        } catch (\Throwable $e) {
            Log::error('Gagal generate QR Code: ' . $e->getMessage());
        }

        return redirect('/admin/assets');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('admin.assets.showAsset', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        return view('admin.assets.editAsset', compact('asset', 'categories'));
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
            'category_id' => 'required|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
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
            'category_id' => $request->category_id,
            'gambar' => $path,
            'deskripsi' => $request->deskripsi,
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