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
    public function index()
    {
        $peminjam = Peminjam::with('asset')
            ->orderBy('created_at', 'desc')
            ->get()
            // group by request_id — gunakan request_id sebagai kunci unik grup
            ->groupBy('request_id')
            ->map(function ($group) {
                $ids = $group->pluck('id')->sort()->values()->toArray();

                // tentukan status ringkasan (prioritas)
                $statuses = $group->pluck('status')->unique()->toArray();
                if (in_array('menunggu', $statuses)) $groupStatus = 'menunggu';
                elseif (in_array('disetujui', $statuses) && !in_array('menunggu', $statuses)) $groupStatus = 'disetujui';
                elseif (in_array('ditolak', $statuses)) $groupStatus = 'ditolak';
                else $groupStatus = $statuses[0] ?? 'menunggu';

                return (object)[
                    // gunakan request_id langsung (bukan md5/arr) supaya mudah dipakai di route
                    'request_id'   => $group->first()->request_id,
                    'nama_peminjam' => $group->first()->nama_peminjam,
                    'assets'       => $group->map(fn($i) => optional($i->asset)->nama_asset . ' (' . $i->jumlah . ')')->implode('<br>'),
                    'total_jumlah' => $group->sum('jumlah'),
                    'tanggal_pinjam' => $group->first()->tanggal_pinjam,
                    'keperluan'    => $group->first()->keperluan,
                    'status'       => $groupStatus,
                    'ids'          => $ids,
                ];
            })->values();

        return view('admin.peminjam.peminjam', compact('peminjam'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $assets = Asset::all();

        return view('admin.peminjam.createPeminjam', compact('users', 'assets'));
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
                    'disetujui_oleh' => auth()->name ?? 'Admin',
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
    public function showGroup(string $request_id)
    {
        $items = Peminjam::with('asset')
            ->where('request_id', $request_id)
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) abort(404, 'Grup peminjaman tidak ditemukan.');

        // summary ringkasan (ambil meta dari first)
        $peminjaman = (object)[
            'request_id' => $request_id,
            'nama_peminjam' => $items->first()->nama_peminjam,
            'tanggal_pinjam' => $items->first()->tanggal_pinjam,
            'keperluan' => $items->pluck('keperluan')->unique()->implode(', '),
            'total_jumlah' => $items->first()->total_pinjam,
            'catatan' => $items->first()->catatan,
            'disetujui_pada' => $items->first()->disetujui_pada,
            'disetujui_oleh' => $items->first()->disetujui_oleh,
            'dikembalikan_pada' => $items->first()->dikembalikan_pada,
            'status' => $items->pluck('status')->contains('menunggu') ? 'menunggu' : ($items->pluck('status')->contains('disetujui') ? 'disetujui' : $items->first()->status),
            'ids' => $items->pluck('id')->toArray(),
            'created_at' => $items->first()->created_at,
        ];

        return view('admin.peminjam.showPeminjam', compact('peminjaman', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $peminjaman = Peminjam::findOrFail($id);
    //     $users = User::all();
    //     $assets = Asset::all();

    //     return view('admin.peminjam.editPeminjam', compact('peminjaman', 'users', 'assets'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $peminjaman = Peminjam::findOrFail($id);

    //     // Validasi dasar
    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'nama_peminjam' => 'required|string|max:255',
    //         'asset_id' => 'required|exists:assets,id',
    //         'jumlah' => 'required|integer|min:1',
    //         'tanggal_pinjam' => 'required|date',
    //         'keperluan' => 'required|string|max:500',
    //         'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan',
    //         'catatan' => 'nullable|string|max:255',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         // Update data peminjaman
    //         $peminjaman->update([
    //             'nama_peminjam' => $request->nama_peminjam,
    //             'asset_id' => $request->asset_id,
    //             'jumlah' => $request->jumlah,
    //             'tanggal_pinjam' => $request->tanggal_pinjam,
    //             'keperluan' => $request->keperluan,
    //             'status' => $request->status,
    //             'catatan' => $request->catatan,
    //         ]);

    //         // Jika status diubah menjadi "disetujui" dari status lain
    //         if ($request->status == 'disetujui' && $peminjaman->getOriginal('status') != 'disetujui') {
    //             $peminjaman->update([
    //                 'disetujui_oleh' => auth()->name ?? 'Admin',
    //                 'disetujui_pada' => now(),
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect()->route('admin.peminjam.index')->with('success', 'Peminjaman berhasil diperbarui!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->withErrors(['error' => 'Gagal memperbarui peminjaman: ' . $e->getMessage()]);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyGroup(string $request_id)
    {
        Peminjam::where('request_id', $request_id)->delete();
        return redirect()->route('admin.peminjam.index')->with('success', 'Grup peminjaman berhasil dihapus.');
    }

    public function approveGroup(string $request_id)
    {
        Peminjam::where('request_id', $request_id)
            ->where('status', 'menunggu')
            ->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::user()->name ?? 'Admin',
                'disetujui_pada' => now()
            ]);

        return redirect()->back()->with('success', 'Semua item pada grup berhasil disetujui.');
    }

    public function rejectGroup(string $request_id)
    {
        Peminjam::where('request_id', $request_id)
            ->where('status', 'menunggu')
            ->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'Semua item pada grup berhasil ditolak.');
    }

    public function returnGroup(string $request_id)
    {
        Peminjam::where('request_id', $request_id)
            ->where('status', 'disetujui')
            ->update([
                'status' => 'dikembalikan',
                'dikembalikan_pada' => now()
            ]);

        return redirect()->back()->with('success', 'Semua item pada grup ditandai dikembalikan.');
    }

    public function printGroup(string $request_id)
    {
        $items = Peminjam::with('asset')->where('request_id', $request_id)->get();
        if ($items->isEmpty()) abort(404);

        // Buat string: "1 CCTV, 2 Tangga, ..."
        $assetsText = $items->map(function ($item) {
            $assetName = optional($item->asset)->nama_asset ?? '-';
            return $item->jumlah . ' ' . $assetName;
        })->implode(', ');

        $summary = (object)[
            'nama_peminjam' => $items->first()->nama_peminjam,
            'tanggal_pinjam' => $items->first()->tanggal_pinjam,
        ];

        return view('admin.peminjam.printPeminjam', compact('items', 'assetsText', 'summary'));
    }
}
