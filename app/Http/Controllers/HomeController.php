<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Peminjam;

class HomeController extends Controller
{
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

            // Statistik dasar
            $totalUsers = User::count();
            $totalAssets = Asset::count();
            $totalPeminjaman = Peminjam::count();
            $countPending = Peminjam::where('status', 'menunggu')->count();

        return view('public.dashboardPublic', compact(
                'peminjam',
                'totalUsers',
                'totalAssets',
                'totalPeminjaman',
                'countPending'
        ));
    }
}
