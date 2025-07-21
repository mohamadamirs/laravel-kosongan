<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $fillable = ['user_id', 'peserta_id', 'foto', 'judul', 'paragraf'];

    // Relasi: Satu Berita dibuat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relasi: Satu Berita dapat terkait dengan satu Peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}