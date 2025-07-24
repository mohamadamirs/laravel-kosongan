<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan; // <-- Import model Ruangan
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Import untuk validasi unik yang lebih baik

class RuanganController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data ruangan, urutkan dari yang terbaru, dan gunakan pagination
        $ruangan = Ruangan::latest()->paginate(10);
        
        // Kirim data ke view admin.ruangan.index
        return view('admin.ruangan.index', compact('ruangan'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.ruangan.create');
    }

    /**
     * Menyimpan ruangan baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'bidang' => 'required|string|max:255|unique:ruangan,bidang',
            'tempat' => 'required|string|max:255',
            'maksimal' => 'required|integer|min:1',
        ]);

        // Buat record baru di database
        Ruangan::create($request->all());

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.ruangan.index')
                         ->with('success', 'Ruangan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu ruangan.
     * Catatan: Method ini jarang digunakan untuk data master sederhana,
     * tapi tetap disediakan sebagai bagian dari resource controller.
     *
     * @param  \App\Models\Ruangan  $ruangan
     * @return \Illuminate\View\View
     */
    public function show(Ruangan $ruangan)
    {
        return view('admin.ruangan.show', compact('ruangan'));
    }

    /**
     * Menampilkan form untuk mengedit data ruangan.
     *
     * @param  \App\Models\Ruangan  $ruangan
     * @return \Illuminate\View\View
     */
    public function edit(Ruangan $ruangan)
    {
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    /**
     * Memperbarui data ruangan di dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ruangan  $ruangan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Ruangan $ruangan)
    {
        // Validasi input dari form edit
        $request->validate([
            // Rule::unique akan mengabaikan data dengan ID saat ini,
            // sehingga kita bisa menyimpan tanpa mengubah nama bidang.
            'bidang' => ['required', 'string', 'max:255', Rule::unique('ruangan')->ignore($ruangan->id)],
            'tempat' => ['required', 'string', 'max:255'],
            'maksimal' => ['required', 'integer', 'min:1'],
        ]);

        // Update record yang ada
        $ruangan->update($request->all());

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.ruangan.index')
                         ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus ruangan dari database.
     *
     * @param  \App\Models\Ruangan  $ruangan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Ruangan $ruangan)
    {
        // Hapus record dari database
        $ruangan->delete();

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.ruangan.index')
                         ->with('success', 'Ruangan berhasil dihapus.');
    }
}