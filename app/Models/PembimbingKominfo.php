<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingKominfo extends Model
{
    use HasFactory;
    
    protected $table = 'pembimbing_kominfo';

    protected $fillable = ['user_id', 'peserta_id', 'bidang', 'telepon','foto'];

    // Relasi: Satu Pembimbing Kominfo milik satu User (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Satu Pembimbing Kominfo membimbing banyak Peserta
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'pembimbing_kominfo_id');
    }
}