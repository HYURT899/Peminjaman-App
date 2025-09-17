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
}
