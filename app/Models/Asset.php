<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory;

    public function peminjams()
    {
        return $this->hasMany(Peminjam::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    protected $fillable = [
        'kode_asset',
        'nama_asset',
        'gambar',
        'deskripsi',
        'stok',
        'category_id'
    ];
}
