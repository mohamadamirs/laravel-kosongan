<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LengkapiDataController extends Controller
{
    /**
     * Menampilkan halaman form untuk melengkapi data.
     */
    public function index()
    {
        // Ambil data peserta yang sedang login
        $peserta = Auth::user()->peserta;
        return view('peserta.lengkapi-data.index', compact('peserta'));
    }

    /**
     * Menyimpan/memperbarui data diri dan surat permohonan.
     */
    public function store(Request $request)
    {
        $peserta = Auth::user()->peserta;

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => ['required', 'string', 'max:20', Rule::unique('peserta', 'nisn')->ignore($peserta->id)],
            'asal_sekolah' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'lahir' => 'required|date',
            'alamat' => 'required|string',
            'mulai_magang' => 'required|date',
            'selesai_magang' => 'required|date|after_or_equal:mulai_magang',
            'file_surat' => 'required|file|mimes:pdf|max:2048', // Hanya PDF, maks 2MB
        ]);

        DB::transaction(function () use ($request, $peserta) {
            // 1. Update data di tabel 'peserta'
            $peserta->update([
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'asal_sekolah' => $request->asal_sekolah,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'telepon' => $request->telepon,
                'lahir' => $request->lahir,
                'alamat' => $request->alamat,
                'mulai_magang' => $request->mulai_magang,
                'selesai_magang' => $request->selesai_magang,
                'status' => 'menunggu_persetujuan', // Ubah status peserta
            ]);

            // 2. Upload file surat dan buat record di 'surat_masuk'
            $path = $request->file('file_surat')->store('surat_masuk', 'public');
            $namaFile = basename($path);

            SuratMasuk::create([
                'peserta_id' => $peserta->id,
                'file' => $namaFile,
                'status' => 'menunggu_verifikasi', // Status awal surat
            ]);
        });
        
        return redirect()->route('peserta.lengkapi-data.index')
                         ->with('success', 'Data Anda telah berhasil diajukan. Mohon tunggu proses verifikasi dari admin.');
    }
}