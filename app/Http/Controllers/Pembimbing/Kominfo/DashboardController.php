<?php

namespace App\Http\Controllers\Pembimbing\Kominfo;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama pembimbing Kominfo.
     */
    public function index()
    {
        // Perubahan di sini: menggunakan relasi pembimbingKominfo
        $jumlahPeserta = Auth::user()->pembimbingKominfo->peserta()->count();
        
        // Arahkan ke view yang benar
        return view('pembimbing.kominfo.dashboard', compact('jumlahPeserta'));
    }

    /**
     * Menampilkan halaman detail lengkap dari satu peserta.
     */
    public function showPeserta(Peserta $peserta)
    {
        // Perubahan di sini: menggunakan relasi pembimbingKominfo
        $pembimbingId = Auth::user()->pembimbingKominfo->id;
        // Perubahan di sini: mengecek kolom pembimbing_kominfo_id
        if ($peserta->pembimbing_kominfo_id !== $pembimbingId) {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMBIMBING PESERTA INI.');
        }

        $peserta->load(['kegiatan' => function ($query) { $query->latest('tanggal'); }, 'absensi' => function ($query) { $query->latest('tanggal'); }, 'izinCuti' => function ($query) { $query->latest('tanggal'); }]);
        
        // Arahkan ke view yang benar
        return view('pembimbing.kominfo.show_peserta', compact('peserta'));
    }
}