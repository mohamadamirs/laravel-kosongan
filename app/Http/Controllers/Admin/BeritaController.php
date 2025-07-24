<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BeritaController extends Controller
{
    /**
     * Menampilkan daftar semua berita.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menggunakan variabel 'berita' sesuai permintaan Anda.
        // Eager load relasi 'user' dan 'peserta' untuk efisiensi query.
        $berita = Berita::with('user', 'peserta')->latest()->paginate(10);

        return view('admin.berita.index', compact('berita'));
    }

    /**
     * Menampilkan form untuk membuat berita baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengambil daftar peserta untuk ditampilkan di dropdown (opsional)
        $peserta = Peserta::orderBy('nama')->get();
        return view('admin.berita.create', compact('peserta'));
    }

    /**
     * Menyimpan berita baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi tidak berubah
        $request->validate([
            'judul' => 'required|string|max:255|unique:berita,judul',
            'paragraf' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto dibuat opsional
            'peserta_id' => 'nullable|exists:peserta,id',
        ]);

        $namaFile = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('berita', 'public');
            $namaFile = basename($path);
        }

        // --- PERBAIKAN UTAMA DI SINI ---
        // Tentukan status berdasarkan siapa yang membuat
        $status = 'menunggu_persetujuan'; // Default status untuk pengajuan
        if (Auth::user()->role == 'admin') {
            // Jika yang membuat adalah admin, langsung terbitkan
            $status = 'diterbitkan';
        }

        Berita::create([
            'judul' => $request->judul,
            'paragraf' => $request->paragraf,
            'peserta_id' => $request->peserta_id,
            'user_id' => Auth::id(),
            'foto' => $namaFile,
            'status' => $status, // <-- Menggunakan variabel status yang sudah ditentukan
        ]);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dibuat.');
    }
    /**
     * Menampilkan form untuk mengedit berita.
     *
     * @param  \App\Models\Berita  $beritum (Laravel akan menggunakan nama singular dari model, 'beritum' mungkin aneh, jadi kita ganti)
     * @return \Illuminate\View\View
     */
    public function edit(Berita $berita) // Menggunakan nama variabel yang lebih jelas
    {
        $peserta = Peserta::orderBy('nama')->get();
        return view('admin.berita.edit', compact('berita', 'peserta'));
    }

    /**
     * Memperbarui data berita di dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255', Rule::unique('berita')->ignore($berita->id)],
            'paragraf' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto tidak wajib saat update
            'peserta_id' => 'nullable|exists:peserta,id',
        ]);

        $data = $request->except('foto'); // Ambil semua data kecuali 'foto'

        // Jika ada file foto baru yang diupload
        if ($request->hasFile('foto')) {
            // 1. Hapus foto lama untuk menghemat storage
            if ($berita->foto) {
                Storage::delete('public/berita/' . $berita->foto);
            }

            // 2. Simpan foto baru dan dapatkan namanya
            $path = $request->file('foto')->store('berita', 'public');
            $data['foto'] = basename($path);
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }
    public function updateStatus(Request $request, Berita $berita)
    {
        // --- PERBAIKAN UTAMA DI SINI ---
        // Tambahkan 'menunggu_persetujuan' ke dalam aturan validasi.
        $request->validate([
            'status' => ['required', Rule::in(['diterbitkan', 'ditolak', 'menunggu_persetujuan'])],
        ]);

        $berita->update(['status' => $request->status]);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Status berita berhasil diperbarui.');
    }

    /**
     * Menghapus berita dari database.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Berita $berita)
    {
        // Hapus file foto yang terkait sebelum menghapus record dari DB
        if ($berita->foto) {
            Storage::delete('public/berita/' . $berita->foto);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
