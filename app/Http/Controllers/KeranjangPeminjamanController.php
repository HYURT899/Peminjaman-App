<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\KeranjangPeminjaman;

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
        $validated = $request->validate([
            'nama_peminjam'   => 'required|string|max:255',
            'tanggal_pinjam'  => 'required|date',
            'keperluan'       => 'required|string|max:500',
            'status'          => 'required|in:menunggu,disetujui,ditolak',
            'catatan'         => 'nullable|string|max:255',
        ]);

        $items = KeranjangPeminjaman::where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $peminjaman = Peminjam::create([
                    'user_id'       => Auth::id(),
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'asset_id'      => $item->asset_id,
                    'jumlah'        => $item->jumlah,
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'keperluan'     => $validated['keperluan'],
                    'status'        => $validated['status'],
                    'catatan'       => $validated['catatan'] ?? null,
                ]);

                // Jika langsung disetujui, isi kolom approval
                if ($validated['status'] === 'disetujui') {
                    $peminjaman->update([
                        'disetujui_oleh' => Auth::user()->name ?? 'Admin',
                        'disetujui_pada' => now(),
                    ]);
                }
            }

            // Kosongkan keranjang
            KeranjangPeminjaman::where('user_id', Auth::id())->delete();

            DB::commit();
            return redirect()->view('public.keranjang.keranjang')->with('success', 'Pengajuan peminjaman berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal submit peminjaman: ' . $e->getMessage()]);
        }
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
