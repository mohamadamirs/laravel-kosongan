<?php

namespace App\Http\Controllers\Pembimbing\Instansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    /**
     * Menampilkan daftar lengkap peserta yang dibimbing dalam bentuk tabel.
     */
    public function index()
    {
        // 1. Ambil profil pembimbing yang login
        $pembimbing = Auth::user()->pembimbingInstansi;

        // 2. Ambil SEMUA peserta yang terhubung dengannya, dengan paginasi
        $pesertaBimbingan = $pembimbing->peserta()->with('user')->paginate(15);
        
        // 3. Kirim data DAFTAR ini ke view 'peserta.index'
        return view('pembimbing.instansi.peserta.index', compact('pesertaBimbingan'));
    }
}