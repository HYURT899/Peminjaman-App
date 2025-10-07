<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Peminjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data ringkasan (admin)
        $totalUsers = User::count();
        $totalAssets = Asset::count();
        $totalPeminjaman = Peminjam::count();

        $recentPeminjaman = Peminjam::with('asset')
            ->latest()
            ->take(8)
            ->get();

        // --- Data untuk tampilan admin: group by nama_peminjam ---
        $peminjam = Peminjam::with('asset')
            ->get()
            ->groupBy('nama_peminjam')
            ->map(function ($group) {
                $assetsLines = $group->map(function ($item) {
                    // Perbaikan: Hindari optional() atau konversi ke string
                    $assetName = $item->asset ? $item->asset->nama_asset : '-';
                    $assetCode = $item->asset ? $item->asset->kode_asset : '-';
                    return $assetName . ' - ' . $assetCode . ' (' . $item->jumlah . ')';
                })->toArray();

                return (object) [
                    'nama_peminjam'  => $group->first()->nama_peminjam,
                    'assets'         => implode('<br>', $assetsLines),
                    'status'         => $group->first()->status,
                    'id'             => $group->first()->id,
                    'ids'            => $group->pluck('id')->toArray(),
                ];
            })->values();

        // --- Data untuk tampilan user: daftar peminjaman miliknya ---
        $peminjamans = Peminjam::with('asset')->latest()->get();

        $countPending = $peminjamans->where('status', 'menunggu')->count();
        $countApproved = $peminjamans->where('status', 'disetujui')->count();

        return view('public.dashboard', compact(
            'user',
            'peminjam', 
            'peminjamans', 
            'countPending',
            'countApproved',
            'totalUsers',
            'totalAssets',
            'totalPeminjaman',
            'recentPeminjaman'
        ));
    }
}