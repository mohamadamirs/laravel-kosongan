<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingInstansi extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_instansi';

    protected $fillable = ['user_id', 'peserta_id', 'bidang', 'telepon'];

    // Relasi: Satu Pembimbing Instansi milik satu User (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Satu Pembimbing Instansi membimbing banyak Peserta
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'pembimbing_instansi_id');
    }
}