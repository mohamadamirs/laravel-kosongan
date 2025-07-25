<?php

namespace App\Http\Controllers\Pembimbing\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama pembimbing dengan data ringkasan.
     */
    public function index()
    {
        // 1. Hitung jumlah peserta yang dibimbing oleh pembimbing yang login
        $jumlahPeserta = Auth::user()->pembimbingInstansi->peserta()->count();
        
        // 2. Kirim data JUMLAH ini ke view 'dashboard'
        return view('pembimbing.instansi.dashboard', compact('jumlahPeserta'));
    }

    /**
     * Menampilkan halaman detail lengkap dari satu peserta.
     * Method ini tetap di sini karena diakses dari halaman daftar peserta.
     */
    public function showPeserta(Peserta $peserta)
    {
        $pembimbingId = Auth::user()->pembimbingInstansi->id;
        if ($peserta->pembimbing_instansi_id !== $pembimbingId) {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMBIMBING PESERTA INI.');
        }

        // Paginate setiap relasi
        $kegiatan = $peserta->kegiatan()->latest('tanggal')->paginate(5);
        $absensi = $peserta->absensi()->latest('tanggal')->paginate(5);
        $izinCuti = $peserta->izinCuti()->latest('tanggal')->paginate(5);

        return view('pembimbing.instansi.show_peserta', compact('peserta', 'kegiatan', 'absensi', 'izinCuti'));
    }
}