<?php

namespace App\Models;

use App\Models\KeranjangPeminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function peminjaman()
    {
        return $this->hasMany(Peminjam::class);
    }

    public function keranjangPeminjam(){
        return $this->hasMany(KeranjangPeminjaman::class);
    }

    protected $fillable = ['kode_asset', 'nama_asset', 'gambar', 'qr_code', 'deskripsi', 'category_id'];
}
