<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data peserta lengkap dengan relasi ke pembimbing dan user pembimbing
        // Ini adalah cara paling efisien menggunakan Eager Loading
        $peserta = Auth::user()->peserta()
                     ->with(['pembimbingInstansi.user', 'pembimbingKominfo.user'])
                     ->first();
        
        return view('peserta.dashboard', compact('peserta'));
    }
}