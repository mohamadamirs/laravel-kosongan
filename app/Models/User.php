<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    // Relasi: Satu User bisa menjadi satu Peserta
    public function peserta()
    {
        return $this->hasOne(Peserta::class, 'user_id');
    }

    // Relasi: Satu User bisa menjadi satu Pembimbing Instansi
    public function pembimbingInstansi()
    {
        return $this->hasOne(PembimbingInstansi::class, 'user_id');
    }

    // Relasi: Satu User bisa menjadi satu Pembimbing Kominfo
    public function pembimbingKominfo()
    {
        return $this->hasOne(PembimbingKominfo::class, 'user_id');
    }

    // Relasi: Satu User bisa membuat banyak Berita
    public function berita()
    {
        return $this->hasMany(Berita::class, 'user_id');
    }
}