<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Contoh data yang bisa dikirim ke dashboard admin
        $jumlahPeserta = Peserta::count();
        $jumlahAdmin = User::where('role', 'admin')->count();
        $jumlahPembimbing = User::whereIn('role', ['pembimbing_instansi', 'pembimbing_kominfo'])->count();

        return view('admin.dashboard', compact('jumlahPeserta', 'jumlahAdmin', 'jumlahPembimbing'));
    }
}