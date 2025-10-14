<?php

namespace App\Models;

use Illuminate\Support\Str;
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
        'request_id'
    ];
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // Function untuk mengisi kolom request_id di tabel peminjam
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->request_id)) {
                $model->request_id = (string) Str::uuid();
            }
        });
    }
}
