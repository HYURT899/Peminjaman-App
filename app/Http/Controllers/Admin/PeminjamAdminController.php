<?php

namespace App\Http\Controllers\Admin;

use Dompdf\Options;
use App\Models\Asset;
use App\Models\Peminjam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use PhpOffice\PhpWord\TemplateProcessor;

class PeminjamAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjam = Peminjam::all();
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
        $peminjaman = Peminjam::with(['asset'])->findOrFail($id);

        return view('admin.peminjam.show', compact('peminjaman'));
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

    // APPROVE peminjaman
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjam::findOrFail($id);

            $peminjaman->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->name ?? 'Admin', // Simpan nama admin yang approve
                'disetujui_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.peminjam.index')->with('success', 'Peminjaman berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.peminjam.index')
                ->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    // REJECT peminjaman
    public function reject($id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjam::findOrFail($id);

            $peminjaman->update([
                'status' => 'ditolak',
                'disetujui_oleh' => auth()->name ?? 'Admin', // Simpan nama admin yang reject
                'disetujui_pada' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.peminjam.index')->with('success', 'Peminjaman berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.peminjam.index')
                ->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }
    }

    // RETURN peminjaman (tandai sudah dikembalikan)
    public function return($id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjam::findOrFail($id);

            $peminjaman->update([
                'status' => 'dikembalikan',
                // disetujui_oleh tidak diubah karena sudah ada dari sebelumnya
            ]);

            DB::commit();

            return redirect()->route('admin.peminjam.index')->with('success', 'Peminjaman berhasil ditandai sebagai dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.peminjam.index')
                ->with('error', 'Gagal update status pengembalian: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        // Ambil data peminjaman + asset
        $peminjaman = Peminjam::with('asset')->findOrFail($id);

        // Load template word
        $template = new TemplateProcessor(storage_path('templates/form_peminjam.docx'));

        // Set data ke template
        $template->setValue('nama_peminjam', $peminjaman->nama_peminjam);
        $template->setValue('nama_asset', $peminjaman->asset->nama_asset);
        $template->setValue('jumlah', $peminjaman->jumlah);
        $template->setValue('tanggal_pinjam', $peminjaman->tanggal_pinjam);
        $template->setValue('keperluan', $peminjaman->keperluan);

        // Nama file hasil
        $fileName = 'peminjaman-' . $peminjaman->id . '.docx';
        $path = storage_path($fileName);

        // Simpan hasil
        $template->saveAs($path);

        // Download otomatis
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function cetak($id)
    {
        $peminjam = Peminjam::with('asset')->findOrFail($id);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = Pdf::loadView('admin.peminjam.cetak', compact('peminjam'));
        return $pdf->stream('peminjam.pdf'); // << stream = preview dulu, bukan langsung download
    }
}
