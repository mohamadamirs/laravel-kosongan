<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratMasukController extends Controller
{
    /**
     * Menampilkan daftar semua surat masuk yang diajukan.
     */
    public function index()
    {
        // Eager load relasi 'peserta' untuk efisiensi dan menampilkan nama
        $suratMasuk = SuratMasuk::with('peserta')->latest()->paginate(15);
        
        return view('admin.surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Memperbarui status surat masuk DAN status peserta terkait.
     * Ini adalah method kunci untuk fitur ini.
     */
    public function updateStatus(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'status' => ['required', Rule::in(['diterima', 'ditolak'])],
        ]);

        // Gunakan transaksi database untuk memastikan kedua update berhasil
        DB::transaction(function () use ($request, $suratMasuk) {
            // 1. Update status surat masuk itu sendiri
            $suratMasuk->update(['status' => $request->status]);

            // 2. Update status peserta yang terkait
            if ($suratMasuk->peserta) {
                // Tentukan status peserta berdasarkan status surat
                $statusPeserta = ($request->status == 'diterima') ? 'aktif' : 'ditolak';
                $suratMasuk->peserta->update(['status' => $statusPeserta]);
            }
        });

        return redirect()->route('admin.surat-masuk.index')
                         ->with('success', 'Status permohonan berhasil diperbarui.');
    }

    /**
     * Menghapus data surat masuk (misalnya jika ada duplikasi atau kesalahan).
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        // Hapus file dari storage sebelum menghapus record DB
        if ($suratMasuk->file) {
            // Pastikan Anda menggunakan disk yang benar (public)
            Storage::disk('public')->delete('surat_masuk/' . $suratMasuk->file);
        }

        $suratMasuk->delete();

        return redirect()->route('admin.surat-masuk.index')
                         ->with('success', 'Data surat masuk berhasil dihapus.');
    }
}