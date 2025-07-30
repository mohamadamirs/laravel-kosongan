<?php

namespace App\Http\Controllers\Pembimbing\Lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    /**
     * Menampilkan daftar lengkap peserta yang dibimbing.
     */
    public function index()
    {
        // Perubahan di sini: menggunakan relasi pembimbingKominfo
        $pembimbing = Auth::user()->pembimbingKominfo;
        $pesertaBimbingan = $pembimbing->peserta()->with('user')->paginate(15);
        
        // Arahkan ke view yang benar
        return view('pembimbing.lapangan.peserta.index', compact('pesertaBimbingan'));
    }
}