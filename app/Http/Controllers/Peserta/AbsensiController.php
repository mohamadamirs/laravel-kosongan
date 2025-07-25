<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- Penting: Import Carbon untuk manipulasi waktu

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi dan riwayatnya.
     */
    public function index()
    {
        $pesertaId = Auth::user()->peserta->id;

        // --- PENGATURAN JENDELA WAKTU ABSENSI ---
        $jamMulaiAbsen = Carbon::parse('07:00');
        $jamSelesaiAbsen = Carbon::parse('10:00');
        // -----------------------------------------

        // 1. Cek apakah peserta sudah absen HARI INI
        $sudahAbsenHariIni = Absensi::where('peserta_id', $pesertaId)
                                    ->whereDate('tanggal', today())
                                    ->exists();

        // 2. Cek apakah SEKARANG berada dalam jendela waktu absensi
        $bisaAbsenSekarang = now()->between($jamMulaiAbsen, $jamSelesaiAbsen);
        
        // 3. Ambil semua riwayat absensi
        $riwayatAbsensi = Absensi::where('peserta_id', $pesertaId)
                                 ->latest('tanggal')
                                 ->paginate(15);

        // 4. Kirim semua data yang diperlukan ke view
        return view('peserta.absensi.index', compact(
            'sudahAbsenHariIni', 
            'riwayatAbsensi',
            'bisaAbsenSekarang', // <-- Kirim status apakah bisa absen atau tidak
            'jamMulaiAbsen',    // <-- Kirim untuk ditampilkan di view
            'jamSelesaiAbsen'   // <-- Kirim untuk ditampilkan di view
        ));
    }

    /**
     * Menyimpan data absensi baru untuk hari ini.
     */
    public function store(Request $request)
    {
        $pesertaId = Auth::user()->peserta->id;

        // --- PENGATURAN JENDELA WAKTU ABSENSI ---
        $jamMulaiAbsen = Carbon::parse('07:00');
        $jamSelesaiAbsen = Carbon::parse('10:00');
        // -----------------------------------------

        // Validasi #1: Cek apakah di luar jendela waktu
        if (!now()->between($jamMulaiAbsen, $jamSelesaiAbsen)) {
            return redirect()->route('peserta.absensi.index')
                             ->with('error', 'Absensi tidak dapat dilakukan di luar jam yang ditentukan.');
        }

        // Validasi #2: Cek apakah sudah absen hari ini
        $sudahAbsen = Absensi::where('peserta_id', $pesertaId)
                             ->whereDate('tanggal', today())
                             ->exists();

        if ($sudahAbsen) {
            return redirect()->route('peserta.absensi.index')
                             ->with('error', 'Anda sudah melakukan absensi untuk hari ini.');
        }

        // Buat record absensi baru
        Absensi::create([
            'peserta_id' => $pesertaId,
            'tanggal' => now(), // Simpan waktu lengkap (tanggal dan jam)
            'status' => 'Hadir',
        ]);

        return redirect()->route('peserta.absensi.index')
                         ->with('success', 'Absensi berhasil dicatat pada pukul ' . now()->format('H:i') . '. Terima kasih!');
    }
}