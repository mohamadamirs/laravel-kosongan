<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;
    
    protected $table = 'peserta';

    protected $fillable = [
        'user_id', 'pembimbing_instansi_id', 'pembimbing_kominfo_id', 'nisn', 
        'gambar', 'asal_sekolah', 'kelas', 'jurusan', 'telepon', 'nama', 'lahir', 
        'alamat', 'status', 'mulai_magang', 'selesai_magang'
    ];

    // Relasi: Satu Peserta milik satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Satu Peserta dibimbing oleh satu Pembimbing Instansi
    public function pembimbingInstansi()
    {
        return $this->belongsTo(PembimbingInstansi::class, 'pembimbing_instansi_id');
    }

    // Relasi: Satu Peserta dibimbing oleh satu Pembimbing Kominfo
    public function pembimbingKominfo()
    {
        return $this->belongsTo(PembimbingKominfo::class, 'pembimbing_kominfo_id');
    }

    // Relasi: Satu Peserta memiliki banyak Kegiatan
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'peserta_id');
    }

    // Relasi: Satu Peserta memiliki banyak data Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'peserta_id');
    }

    // Relasi: Satu Peserta memiliki banyak Izin Cuti
    public function izinCuti()
    {
        return $this->hasMany(IzinCuti::class, 'peserta_id');
    }

    // Relasi: Satu Peserta memiliki banyak Surat Masuk
    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'peserta_id');
    }

    // Relasi: Satu Peserta bisa menjadi subjek banyak Berita
    public function berita()
    {
        return $this->hasMany(Berita::class, 'peserta_id');
    }
}