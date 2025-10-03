<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $asset_id
 * @property int $jumlah
 *
 * @property-read User $user
 * @property-read Asset $asset
 */
class KeranjangPeminjaman extends Model
{
    protected $table = 'keranjang_peminjaman'; // Tentukan nama tabel

    protected $fillable = [
        'user_id',
        'asset_id',
        'jumlah',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
