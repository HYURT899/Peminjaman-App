<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peminjam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dataDashboard()
    {
        // Hitung jumlah asset dan peminjam
        $totalAssets = DB::table('assets')->count();
        $totalPeminjams = Peminjam::distinct('nama_peminjam')->count('nama_peminjam');
        $totalUsers = DB::table('users')->count();

        // Hitung asset per kategori
        $kategori = DB::table('assets')
            ->join('categories', 'assets.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(*) as total'))
            ->groupBy('categories.name')
            ->get();

        return view('admin.dashboardAdmin', compact('totalAssets', 'kategori', 'totalPeminjams', 'totalUsers'));
    }
}
