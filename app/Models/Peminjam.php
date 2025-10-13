<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    protected $fillable = [
        'nama_peminjam', 
        'asset_id',
        'jumlah',
        'tanggal_pinjam',
        'keperluan',
        'status',
        'catatan',
        'disetujui_oleh',
        'disetujui_pada',
    ];
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public static function generateKodePeminjam()
    {
        $date = now()->format('Ymd');
        $prefix = "PMJ-{$date}-";

        // Cari kode terakhir untuk hari ini
        $lastPeminjam = self::where('kode_peminjam', 'like', $prefix . '%')
            ->orderBy('kode_peminjam', 'desc')
            ->first();

        if ($lastPeminjam) {
            // Extract angka terakhir dan increment
            $lastNumber = (int) substr($lastPeminjam->kode_peminjam, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada untuk hari ini, mulai dari 0001
            $nextNumber = 1;
        }

        // Format angka dengan leading zeros
        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $formattedNumber;
    }
}
