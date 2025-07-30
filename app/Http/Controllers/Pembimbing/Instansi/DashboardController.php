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
        // 1. Dapatkan profil pembimbing dari user yang login
        $pembimbing = Auth::user()->pembimbingInstansi;

        // --- PERBAIKAN UTAMA DI SINI ---
        // 2. Periksa apakah profil pembimbing ADA. Jika tidak, ada masalah.
        if (!$pembimbing) {
            // Ini adalah kasus darurat. Seharusnya tidak terjadi jika middleware 'role' bekerja.
            // Arahkan keluar dengan pesan error.
            Auth::logout();
            return redirect('/login')->with('error', 'Profil Pembimbing Guru/Dosen Anda tidak ditemukan. Harap hubungi Admin.');
        }

        // 3. Jika profil ada, hitung jumlah pesertanya.
        $jumlahPeserta = $pembimbing->peserta()->count();
        
        // 4. Kirim data ke view
        return view('pembimbing.instansi.dashboard', compact('jumlahPeserta'));
    }

    /**
     * Menampilkan halaman detail lengkap dari satu peserta.
     */
    public function showPeserta(Peserta $peserta)
    {
        $pembimbing = Auth::user()->pembimbingInstansi;
        
        // Periksa lagi di sini untuk keamanan
        if (!$pembimbing) {
             Auth::logout();
             return redirect('/login')->with('error', 'Profil Pembimbing Guru/Dosen Anda tidak ditemukan.');
        }

        // Lakukan Pengecekan Otorisasi
        if ($peserta->pembimbing_instansi_id !== $pembimbing->id) {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMBIMBING PESERTA INI.');
        }

        $peserta->load(['kegiatan', 'absensi', 'izinCuti']);

        $kegiatan = $peserta->kegiatan()->latest('tanggal')->paginate(5, ['*'], 'kegiatan_page');
        $absensi = $peserta->absensi()->latest('tanggal')->paginate(5, ['*'], 'absensi_page');
        $izinCuti = $peserta->izinCuti()->latest('tanggal')->paginate(5, ['*'], 'izin_cuti_page');


        return view('pembimbing.instansi.show_peserta', compact('peserta', 'kegiatan', 'absensi', 'izinCuti'));
    }
}