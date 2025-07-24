<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Ruangan; // Pastikan ini di-import
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan data dinamis.
     */
    public function index()
    {
        // 1. Ambil data ruangan
        $ruanganTersedia = Ruangan::orderBy('bidang')->get();

        // 2. Ambil data berita
        $beritaTerbaru = Berita::where('status', 'diterbitkan')
                               ->latest()
                               ->take(6)
                               ->get();

        // 3. PASTIKAN KEDUA VARIABEL DIKIRIM MENGGUNAKAN 'compact'
        return view('welcome', compact('beritaTerbaru', 'ruanganTersedia'));
    }
}