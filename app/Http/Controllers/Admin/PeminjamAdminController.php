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
        // Ambil semua data peminjaman beserta relasi asset-nya
        $peminjam = Peminjam::with('asset')
            ->get()

            // Grouping data berdasarkan kombinasi unik:
            // nama peminjam + tanggal pinjam + keperluan
            // Tujuannya agar setiap peminjaman yang benar-benar berbeda (meski nama sama)
            // tetap dianggap sebagai kelompok terpisah.
            ->groupBy(function ($item) {
                // Bersihkan nama agar konsisten (hapus spasi & lowercase)
                $nama = trim(strtolower($item->nama_peminjam ?? ''));

                // Format tanggal pinjam ke bentuk YYYY-MM-DD agar konsisten
                $tgl = $item->tanggal_pinjam
                    ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('Y-m-d')
                    : 'no-date';

                // Keperluan juga dibersihkan agar tidak beda hanya karena kapitalisasi/spasi
                $keperluan = trim(strtolower($item->keperluan ?? 'no-keperluan'));

                // Gabungkan jadi satu string unik sebagai kunci grup
                // Contoh: "asep|2025-10-09|perbaikan laptop"
                return $nama . '|' . $tgl . '|' . $keperluan;
            })

            // Setelah digroup, lakukan transformasi pada setiap grup
            ->map(function ($group) {

                // Buat daftar asset yang dipinjam dalam grup ini
                $assetsLines = $group->map(function ($item) {
                    $assetName = optional($item->asset)->nama_asset ?? '-';
                    $assetCode = optional($item->asset)->kode_asset ?? '-';
                    return $assetName . ' - ' . $assetCode . ' (' . $item->jumlah . ')';
                })->toArray();

                // Return satu objek "ringkasan" dari grup peminjaman
                return (object) [

                    // Hash unik (md5 dari semua id peminjaman di grup ini)
                    // berguna kalau nanti butuh ID group khusus.
                    'group_key' => md5($group->pluck('id')->implode(',')),

                    // Nama peminjam (ambil dari record pertama dalam grup)
                    'nama_peminjam' => $group->first()->nama_peminjam,

                    // Gabungkan semua asset ke bentuk HTML (pakai <br> untuk tampilan tabel)
                    'assets' => implode('<br>', $assetsLines),

                    // Hitung total jumlah barang dalam grup ini
                    'total_jumlah' => $group->sum('jumlah'),

                    // Ambil tanggal pinjam dari record pertama (karena semua sama dalam grup)
                    'tanggal_pinjam' => $group->first()->tanggal_pinjam,

                    // Gabungkan semua keperluan unik dalam grup (kalau lebih dari satu)
                    'keperluan' => $group->pluck('keperluan')->unique()->implode(', '),

                    // Status ambil dari record pertama (umumnya sama untuk satu grup)
                    'status' => $group->first()->status,

                    // Simpan semua ID peminjaman dalam grup ini (penting untuk aksi massal)
                    'ids' => $group->pluck('id')->toArray(),
                ];
            })

            // Ubah hasilnya jadi urutan array tanpa key (numerik)
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
    public function show(string $group_key)
    {
        // ambil semua peminjaman dengan relasi asset (atau batasi jika datanya besar)
        $all = Peminjam::with('asset')->get();

        // rebuild grouping dengan logika yang sama seperti index()
        $groups = $all->groupBy(function ($item) {
            $nama = trim(strtolower($item->nama_peminjam ?? ''));
            $tgl  = $item->tanggal_pinjam
                ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('Y-m-d')
                : 'no-date';
            $keperluan = trim(strtolower($item->keperluan ?? 'no-keperluan'));
            return $nama . '|' . $tgl . '|' . $keperluan;
        })->map(function ($group) {
            $assetsLines = $group->map(function ($item) {
                $assetName = optional($item->asset)->nama_asset ?? '-';
                $assetCode = optional($item->asset)->kode_asset ?? '-';
                return $assetName . ' - ' . $assetCode . ' (' . $item->jumlah . ')';
            })->toArray();

            // ids diurutkan supaya group_key konsisten
            $ids = $group->pluck('id')->sort()->values()->toArray();

            return (object) [
                'group_key'      => md5(implode(',', $ids)),
                'nama_peminjam'  => $group->first()->nama_peminjam,
                'assets'         => implode('<br>', $assetsLines),
                'total_jumlah'   => $group->sum('jumlah'),
                'tanggal_pinjam' => $group->first()->tanggal_pinjam,
                'keperluan'      => $group->pluck('keperluan')->unique()->implode(', '),
                'status'         => $group->first()->status,
                'catatan'        => $group->first()->catatan,
                'disetujui_oleh' => $group->first()->disetujui_oleh,
                'disetujui_pada' => $group->first()->disetujui_pada,
                'dikembalikan_pada' => $group->first()->dikembalikan_pada,
                'created_at'     => $group->first()->created_at,
                'ids'            => $ids,
            ];
        })->values();

        // cari grup yang sesuai group_key
        $peminjaman = $groups->firstWhere('group_key', $group_key);

        if (! $peminjaman) {
            // grup tidak ditemukan -> 404
            abort(404, 'Grup peminjaman tidak ditemukan.');
        }

        // ambil semua row Peminjam asli yang termasuk di grup ini
        $items = Peminjam::with('asset')->whereIn('id', $peminjaman->ids)
            ->orderBy('id') // urutkan sesuai kebutuhan
            ->get();

        return view('admin.peminjam.show', compact('peminjaman', 'items'));
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
            ->update([
                'status' => 'dikembalikan',
                'dikembalikan_pada' => now()
            ]);

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
