<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data spesifik untuk peserta yang login
        $peserta = Auth::user()->peserta->load('pembimbingInstansi', 'pembimbingKominfo');
        $kegiatanTerakhir = $peserta->kegiatan()->latest()->take(5)->get();

        return view('peserta.dashboard', compact('peserta', 'kegiatanTerakhir'));
    }
}