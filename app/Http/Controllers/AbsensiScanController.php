<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiScanController extends Controller
{
    /**
     * Mencatat absensi peserta dari hasil pindaian QR code.
     */
    public function recordAttendance(Request $request, Peserta $peserta)
    {
        // --- KEAMANAN PENTING: Validasi Tanda Tangan URL ---
        // Jika URL tidak memiliki tanda tangan yang valid, tolak akses.
        if (! $request->hasValidSignature()) {
            return view('absensi.scan_result', ['status' => 'error', 'message' => 'QR Code tidak valid atau sudah kedaluwarsa.']);
        }

        // --- Logika Absensi (Sama seperti AbsensiController milik peserta) ---
        $jamMulaiAbsen = Carbon::parse('07:00');
        $jamSelesaiAbsen = Carbon::parse('10:00');

        if (!now()->between($jamMulaiAbsen, $jamSelesaiAbsen)) {
            return view('absensi.scan_result', ['status' => 'error', 'message' => 'Absensi tidak dapat dilakukan di luar jam yang ditentukan (' . $jamMulaiAbsen->format('H:i') . ' - ' . $jamSelesaiAbsen->format('H:i') . ').']);
        }

        $sudahAbsen = Absensi::where('peserta_id', $peserta->id)
                             ->whereDate('tanggal', today())
                             ->exists();

        if ($sudahAbsen) {
            return view('absensi.scan_result', ['status' => 'info', 'message' => 'Anda sudah melakukan absensi untuk hari ini.']);
        }

        Absensi::create([
            'peserta_id' => $peserta->id,
            'tanggal' => now(),
            'status' => 'Hadir',
        ]);

        return view('absensi.scan_result', ['status' => 'success', 'message' => 'Absensi untuk ' . $peserta->nama . ' berhasil dicatat pada pukul ' . now()->format('H:i') . '.']);
    }
}