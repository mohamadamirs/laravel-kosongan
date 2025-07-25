<?php

namespace App\Http\Controllers\Pembimbing\Instansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    /**
     * Menampilkan daftar peserta yang dibimbing.
     */
    public function index()
    {
        $pembimbing = Auth::user()->pembimbingInstansi;
        $pesertaBimbingan = $pembimbing->peserta()->with('user')->paginate(15);
        
        // Arahkan ke view baru yang akan kita buat
        return view('pembimbing.instansi.peserta.index', compact('pesertaBimbingan'));
    }
}