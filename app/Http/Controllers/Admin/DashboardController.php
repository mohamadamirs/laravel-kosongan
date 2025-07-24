<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // <-- Penting: Import model User
use App\Models\Peserta; // Kita tetap butuh ini untuk metrik lain
use App\Models\Ruangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() 
    {
        // === CARA BARU YANG AKURAT: Menghitung berdasarkan role di tabel 'users' ===

        // Menghitung jumlah akun yang memiliki peran 'peserta'
        $jumlahPeserta = User::where('role', 'peserta')->count();

        // Menghitung jumlah akun yang memiliki peran 'pembimbing_instansi'
        $jumlahPembimbingInstansi = User::where('role', 'pembimbing_instansi')->count();

        // Menghitung jumlah akun yang memiliki peran 'pembimbing_kominfo'
        $jumlahPembimbingKominfo = User::where('role', 'pembimbing_kominfo')->count();

        // === METRIK LAIN YANG LEBIH BERGUNA ===

        // Menghitung jumlah pendaftar baru yang statusnya masih menunggu persetujuan
        // Ini lebih informatif daripada total peserta saja.
        $jumlahPendaftarBaru = Peserta::where('status', 'menunggu_persetujuan')->count();

        // Menghitung jumlah ruangan tetap sama karena ini adalah data master
        $jumlahRuangan = Ruangan::count();

        // Kirim semua variabel yang sudah akurat ini ke view
        return view('admin.dashboard', compact(
            'jumlahPeserta', 
            'jumlahPembimbingInstansi', 
            'jumlahPembimbingKominfo', 
            'jumlahRuangan',
            'jumlahPendaftarBaru' // <-- Jangan lupa kirim variabel baru
        ));
    }
}