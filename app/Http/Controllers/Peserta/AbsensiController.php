<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi dan riwayatnya.
     */
    public function index()
    {
        $pesertaId = Auth::user()->peserta->id;

        // 1. Cek apakah peserta sudah absen HARI INI
        $sudahAbsenHariIni = Absensi::where('peserta_id', $pesertaId)
                                    ->whereDate('tanggal', today())
                                    ->exists();

        // 2. Ambil semua riwayat absensi milik peserta ini
        $riwayatAbsensi = Absensi::where('peserta_id', $pesertaId)
                                 ->latest('tanggal') // Urutkan dari tanggal terbaru
                                 ->paginate(15);

        // 3. Kirim kedua data tersebut ke view
        return view('peserta.absensi.index', compact('sudahAbsenHariIni', 'riwayatAbsensi'));
    }

    /**
     * Menyimpan data absensi baru untuk hari ini.
     */
    public function store(Request $request)
    {
        $pesertaId = Auth::user()->peserta->id;

        // Validasi untuk mencegah absensi ganda pada hari yang sama
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
            'tanggal' => today(),
            'status' => 'Hadir', // Status default
        ]);

        return redirect()->route('peserta.absensi.index')
                         ->with('success', 'Absensi berhasil dicatat. Terima kasih!');
    }
}