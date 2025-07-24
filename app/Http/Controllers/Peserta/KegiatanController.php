<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Menampilkan daftar kegiatan harian milik peserta yang sedang login.
     */
    public function index()
    {
        // Ambil kegiatan HANYA milik peserta yang sedang login
        $kegiatan = Auth::user()->peserta->kegiatan()
                        ->latest('tanggal') // Urutkan dari tanggal terbaru
                        ->paginate(10);
        
        return view('peserta.kegiatan.index', compact('kegiatan'));
    }

    /**
     * Menampilkan form untuk membuat kegiatan baru.
     */
    public function create()
    {
        return view('peserta.kegiatan.create');
    }

    /**
     * Menyimpan kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto opsional
        ]);

        $data = $request->except('foto');
        $data['peserta_id'] = Auth::user()->peserta->id;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kegiatan', 'public');
            $data['foto'] = basename($path);
        }

        Kegiatan::create($data);

        return redirect()->route('peserta.kegiatan.index')
                         ->with('success', 'Kegiatan harian berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kegiatan.
     */
    public function edit(Kegiatan $kegiatan)
    {
        // PENTING: Pengecekan Otorisasi
        // Pastikan peserta hanya bisa mengedit kegiatannya sendiri
        if ($kegiatan->peserta_id !== Auth::user()->peserta->id) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('peserta.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Memperbarui kegiatan di database.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        // PENTING: Pengecekan Otorisasi
        if ($kegiatan->peserta_id !== Auth::user()->peserta->id) {
            abort(403, 'AKSES DITOLAK');
        }
        
        $request->validate([
            'tanggal' => 'required|date',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($kegiatan->foto) {
                Storage::disk('public')->delete('kegiatan/' . $kegiatan->foto);
            }
            $path = $request->file('foto')->store('kegiatan', 'public');
            $data['foto'] = basename($path);
        }

        $kegiatan->update($data);

        return redirect()->route('peserta.kegiatan.index')
                         ->with('success', 'Kegiatan harian berhasil diperbarui.');
    }

    /**
     * Menghapus kegiatan dari database.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        // PENTING: Pengecekan Otorisasi
        if ($kegiatan->peserta_id !== Auth::user()->peserta->id) {
            abort(403, 'AKSES DITOLAK');
        }

        // Hapus foto terkait
        if ($kegiatan->foto) {
            Storage::disk('public')->delete('kegiatan/' . $kegiatan->foto);
        }

        $kegiatan->delete();

        return redirect()->route('peserta.kegiatan.index')
                         ->with('success', 'Kegiatan harian berhasil dihapus.');
    }
}