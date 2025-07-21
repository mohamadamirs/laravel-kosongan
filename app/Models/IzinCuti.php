<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinCuti extends Model
{
    use HasFactory;

    protected $table = 'izin_cuti';
    protected $fillable = ['peserta_id', 'keterangan', 'tanggal', 'bukti_foto'];

    // Relasi: Satu record Izin Cuti ini milik satu Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}