<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KeranjangPeminjaman;
use Illuminate\Support\Facades\Auth;

class KeranjangPeminjamanController extends Controller
{
    /**
     * Tambah asset ke keranjang
     */
    public function add(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
        ]);

        $userId = Auth::id();

        // cek apakah asset sudah ada di keranjang user
        $item = KeranjangPeminjaman::where('user_id', $userId)
            ->where('asset_id', $request->asset_id)
            ->first();

        if ($item) {
            // kalau ada, jumlah ditambah
            $item->jumlah = $item->jumlah + 1;
            $item->save();
        } else {
            // kalau belum, buat baru
            KeranjangPeminjaman::create([
                'user_id'  => $userId,
                'asset_id' => $request->asset_id,
                'jumlah'   => 1,
            ]);
        }

        return back()->with('success', 'Asset ditambahkan ke daftar peminjaman.');
    }

    /**
     * Lihat isi keranjang
     */
    public function index()
    {
        $items = KeranjangPeminjaman::where('user_id', Auth::id())
            ->with('asset')
            ->get();

        return view('public.keranjang.keranjang', compact('items'));
    }

    /**
     * Submit keranjang jadi peminjaman
     */
    public function submit(Request $request)
    {
        $items = KeranjangPeminjaman::where('user_id', Auth::id())->get();
        if ($items->isEmpty()) return back()->with('error', 'Keranjang kosong');

        $requestId = Str::uuid()->toString();

        DB::transaction(function () use ($items, $requestId) {
            foreach ($items as $item) {
                Peminjam::create([
                    'request_id' => $requestId,
                    'user_id' => Auth::id(),
                    'nama_peminjam' => Auth::user()->name,
                    'asset_id' => $item->asset_id,
                    'jumlah' => $item->jumlah,
                    'tanggal_pinjam' => now()->toDateString(), // contoh
                    'keperluan' => request('keperluan') ?? '-',
                    'status' => 'menunggu'
                ]);
            }
            KeranjangPeminjaman::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('keranjang.index')->with('success', 'Pengajuan dikirim');
    }

    public function delete($id)
    {
        $item = KeranjangPeminjaman::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, $id)
    {
        $item = KeranjangPeminjaman::findOrFail($id);

        if ($request->action === 'increase') {
            $item->jumlah += 1;
        } elseif ($request->action === 'decrease') {
            if ($item->jumlah > 1) {
                $item->jumlah -= 1;
            } else {
                $item->delete();
                return response()->json(['deleted' => true]);
            }
        }

        $item->save();

        return response()->json([
            'deleted' => false,
            'jumlah' => $item->jumlah
        ]);
    }
}
