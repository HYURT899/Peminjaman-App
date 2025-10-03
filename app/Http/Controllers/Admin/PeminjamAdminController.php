<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\Peminjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PeminjamAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Di Controller - index method
    public function index()
    {
        // ambil semua peminjaman beserta relasi asset
        $peminjam = Peminjam::with('asset')
            ->get()
            ->groupBy('nama_peminjam') // grup per nama peminjam
            ->map(function ($group) {
                // buat array string per asset: "NamaAsset - KODE (jumlah)"
                $assetsLines = $group->map(function ($item) {
                    $assetName = optional($item->asset)->nama_asset ?? '-';
                    $assetCode = optional($item->asset)->kode_asset ?? '-';
                    return $assetName . ' - ' . $assetCode . ' <strong>(' . $item->jumlah . ')</strong>';
                })->toArray();

                return (object) [
                    'nama_peminjam'  => $group->first()->nama_peminjam,
                    'assets'         => implode('<br>', $assetsLines), // nanti diberi {!! !!} di blade
                    'total_jumlah'   => $group->sum('jumlah'), // total semua jumlah
                    'tanggal_pinjam' => $group->first()->tanggal_pinjam, // ambil salah satu (bisa diubah jadi min/max)
                    'keperluan'      => $group->pluck('keperluan')->unique()->implode(', '),
                    'status'         => $group->first()->status, // aturan: ambil yang pertama (ubah sesuai kebutuhan)
                    'id'             => $group->first()->id, // id dari salah satu record (dipakai untuk tombol CRUD)
                    'ids'            => $group->pluck('id')->toArray(), // kalau mau list detail tiap item
                ];
            })
            // jika mau dijadikan koleksi biasa (opsional)
            ->values();

        return view('admin.peminjam.index', compact('peminjam'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $assets = Asset::all();

        return view('admin.peminjam.create', compact('users', 'assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'asset_id' => 'required|exists:assets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'keperluan' => 'required|string|max:500',
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $peminjaman = Peminjam::create([
                'nama_peminjam' => $request->nama_peminjam,
                'asset_id' => $request->asset_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'keperluan' => $request->keperluan,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Jika status langsung "disetujui", set approval info
            if ($request->status == 'disetujui') {
                $peminjaman->update([
                    'disetujui_oleh' => auth()->name ?? 'Admin', // Simpan nama admin
                    'disetujui_pada' => now(),
                ]);
            }

            DB::commit();

            return redirect('/admin/peminjam')->with('success', 'Peminjaman berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambah peminjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil satu record (dipakai untuk header/detail umum)
        $peminjaman = Peminjam::with('asset')->findOrFail($id);

        // Ambil semua peminjaman yang punya nama_peminjam sama (semua item milik user tersebut)
        $allItems = Peminjam::with('asset')
            ->where('nama_peminjam', $peminjaman->nama_peminjam)
            ->get();

        // Total jumlah semua item (opsional, kalau mau ditampilkan)
        $totalJumlah = $allItems->sum('jumlah');

        return view('admin.peminjam.show', compact('peminjaman', 'allItems', 'totalJumlah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peminjaman = Peminjam::findOrFail($id);
        $users = User::all();
        $assets = Asset::all();

        return view('admin.peminjam.edit', compact('peminjaman', 'users', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjam::findOrFail($id);

        // Validasi dasar
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_peminjam' => 'required|string|max:255',
            'asset_id' => 'required|exists:assets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'keperluan' => 'required|string|max:500',
            'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan',
            'catatan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update data peminjaman
            $peminjaman->update([
                'nama_peminjam' => $request->nama_peminjam,
                'asset_id' => $request->asset_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'keperluan' => $request->keperluan,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Jika status diubah menjadi "disetujui" dari status lain
            if ($request->status == 'disetujui' && $peminjaman->getOriginal('status') != 'disetujui') {
                $peminjaman->update([
                    'disetujui_oleh' => auth()->name ?? 'Admin',
                    'disetujui_pada' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.peminjam.index')->with('success', 'Peminjaman berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui peminjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = Peminjam::findOrFail($id);
        $asset->delete();

        return redirect()->route('admin.peminjam.index')->with('success', 'Peminjam berhasil dihapus!');
    }

    public function approve($nama)
    {
        Peminjam::where('nama_peminjam', $nama)
            ->where('status', 'menunggu')
            ->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::user()->name ?? 'Admin',
                'disetujui_pada' => now()
            ]);

        return redirect()->back()->with('success', "Semua peminjaman oleh {$nama} disetujui!");
    }

    public function reject($nama)
    {
        Peminjam::where('nama_peminjam', $nama)
            ->where('status', 'menunggu')
            ->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', "Semua peminjaman oleh {$nama} ditolak!");
    }

    public function return($nama)
    {
        Peminjam::where('nama_peminjam', $nama)
            ->where('status', 'disetujui')
            ->update(['status' => 'dikembalikan']);

        return redirect()->back()->with('success', "Semua peminjaman oleh {$nama} ditandai sebagai dikembalikan!");
    }

    public function print(string $id)
    {
        // ambil 1 record (dipakai untuk meta seperti nama peminjam, tanggal dsb)
        $peminjaman = Peminjam::with('asset')->findOrFail($id);

        // ambil semua peminjaman yang punya nama_peminjam sama
        $allPeminjaman = Peminjam::with('asset')
            ->where('nama_peminjam', $peminjaman->nama_peminjam)
            ->get();

        // buat string seperti: "1 CCTV, 2 Tangga, 3 Laptop"
        $assetsText = $allPeminjaman->map(function ($item) {
            $assetName = optional($item->asset)->nama_asset ?? '-';
            // pastikan ada spasi setelah jumlah: "2 Tangga"
            return $item->jumlah . ' ' . $assetName;
        })->implode(', ');

        // kirim semuanya ke view
        return view('admin.peminjam.print', compact('peminjaman', 'allPeminjaman', 'assetsText'));
    }
}
