<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\KeranjangPeminjaman;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * @property int $id
 * @property string $name
 * @property string $email
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|KeranjangPeminjaman[] $keranjangPeminjam
 * @property-read \Illuminate\Database\Eloquent\Collection|Peminjam[] $peminjaman
 */


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'jabatan', 'gambar'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // public function Role()
    // {
    //     return $this->belongsTo(Role::class);
    // }
    // public function nama()
    // {
    //     return $this->belongsTo(\App\Models\Role::class, 'role');
    // }

    public function peminjaman()
    {
        return $this->hasMany(Peminjam::class);
    }

    public function keranjangPeminjam(){
        return $this->hasMany(KeranjangPeminjaman::class);
    }
}
