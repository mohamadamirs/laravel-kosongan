<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\IzinCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinCutiController extends Controller
{
    /**
     * Menampilkan halaman riwayat dan form pengajuan izin/cuti.
     */
    public function index()
    {
        $riwayatIzin = Auth::user()->peserta->izinCuti()
                           ->latest('tanggal') // Urutkan dari tanggal terbaru
                           ->paginate(10);
        
        return view('peserta.izin-cuti.index', compact('riwayatIzin'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     */
    public function create()
    {
        return view('peserta.izin-cuti.create');
    }

    /**
     * Menyimpan pengajuan izin/cuti baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'keterangan' => 'required|string|max:500',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Bukti foto wajib
        ]);

        $pesertaId = Auth::user()->peserta->id;

        $path = $request->file('bukti_foto')->store('izin_cuti', 'public');
        $namaFile = basename($path);

        IzinCuti::create([
            'peserta_id' => $pesertaId,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $namaFile,
        ]);

        return redirect()->route('peserta.izin-cuti.index')
                         ->with('success', 'Pengajuan izin/cuti Anda berhasil dikirim.');
    }
}