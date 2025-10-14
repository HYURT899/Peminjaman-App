<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Peminjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === Kalau Admin ===
        if ($user->id === 1) {
            // Statistik dasar
            $totalUsers = User::count();
            $totalAssets = Asset::count();
            $totalPeminjaman = Peminjam::count();
            $countPending = Peminjam::where('status', 'menunggu')->count();

            // Ambil semua data peminjaman untuk tabel admin
            $peminjam = Peminjam::with('asset')
                ->get()
                ->groupBy(function ($item) {
                    $nama = trim(strtolower($item->nama_peminjam ?? ''));
                    $tgl = $item->tanggal_pinjam ? Carbon::parse($item->tanggal_pinjam)->format('Y-m-d') : 'no-date';
                    $keperluan = trim(strtolower($item->keperluan ?? 'no-keperluan'));
                    return $nama . '|' . $tgl . '|' . $keperluan;
                })
                ->map(function ($group) {
                    $assetsLines = $group->map(function ($item) {
                        $assetName = optional($item->asset)->nama_asset ?? '-';
                        $assetCode = optional($item->asset)->kode_asset ?? '-';
                        return "$assetName - $assetCode ({$item->jumlah})";
                    })->toArray();

                    return (object) [
                        'group_key' => md5($group->pluck('id')->implode(',')),
                        'nama_peminjam' => $group->first()->nama_peminjam,
                        'assets' => implode('<br>', $assetsLines),
                        'total_jumlah' => $group->sum('jumlah'),
                        'tanggal_pinjam' => $group->first()->tanggal_pinjam,
                        'status' => $group->first()->status,
                        'ids' => $group->pluck('id')->toArray(),
                    ];
                })
                ->values();

            return view('public.dashboard', compact(
                'peminjam',
                'totalUsers',
                'totalAssets',
                'totalPeminjaman',
                'countPending'
            ));
        }

        // === Kalau User Biasa ===
        $totalUsers = User::count();
            $totalAssets = Asset::count();
            $totalPeminjaman = Peminjam::count();
            $countPending = Peminjam::where('status', 'menunggu')->count();

            // Ambil semua data peminjaman untuk tabel admin
            $peminjam = Peminjam::with('asset')
                ->get()
                ->groupBy(function ($item) {
                    $nama = trim(strtolower($item->nama_peminjam ?? ''));
                    $tgl = $item->tanggal_pinjam ? Carbon::parse($item->tanggal_pinjam)->format('Y-m-d') : 'no-date';
                    $keperluan = trim(strtolower($item->keperluan ?? 'no-keperluan'));
                    return $nama . '|' . $tgl . '|' . $keperluan;
                })
                ->map(function ($group) {
                    $assetsLines = $group->map(function ($item) {
                        $assetName = optional($item->asset)->nama_asset ?? '-';
                        $assetCode = optional($item->asset)->kode_asset ?? '-';
                        return "$assetName - $assetCode ({$item->jumlah})";
                    })->toArray();

                    return (object) [
                        'group_key' => md5($group->pluck('id')->implode(',')),
                        'nama_peminjam' => $group->first()->nama_peminjam,
                        'assets' => implode('<br>', $assetsLines),
                        'total_jumlah' => $group->sum('jumlah'),
                        'tanggal_pinjam' => $group->first()->tanggal_pinjam,
                        'status' => $group->first()->status,
                        'ids' => $group->pluck('id')->toArray(),
                    ];
                })
                ->values();

            return view('public.dashboard', compact(
                'peminjam',
                'totalUsers',
                'totalAssets',
                'totalPeminjaman',
                'countPending'
        ));
    }
}
