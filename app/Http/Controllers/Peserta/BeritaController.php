<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    /**
     * Menampilkan form untuk peserta membuat pengajuan berita.
     */
    public function create()
    {
        return view('peserta.berita.create');
    }

    /**
     * Menyimpan pengajuan berita baru dari peserta ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'judul' => 'required|string|max:255',
            'paragraf' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto opsional
        ]);

        $namaFile = null;
        if ($request->hasFile('foto')) {
            // Simpan foto ke storage publik
            $path = $request->file('foto')->store('berita', 'public');
            $namaFile = basename($path);
        }

        // 2. Simpan data ke database dengan status 'menunggu_persetujuan'
        Berita::create([
            'judul' => $request->judul,
            'paragraf' => $request->paragraf,
            'foto' => $namaFile,
            'user_id' => Auth::id(), // ID dari tabel users
            'peserta_id' => Auth::user()->peserta->id, // ID dari tabel peserta
            'status' => 'menunggu_persetujuan', // Ini adalah kuncinya!
        ]);

        // 3. Arahkan kembali ke dashboard peserta dengan pesan sukses
        return redirect()->route('peserta.dashboard')
                         ->with('success', 'Berita Anda berhasil diajukan dan sedang menunggu persetujuan dari Admin.');
    }
}