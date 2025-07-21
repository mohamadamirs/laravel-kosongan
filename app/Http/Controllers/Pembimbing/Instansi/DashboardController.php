<?php

namespace App\Http\Controllers\Pembimbing\Instansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pembimbing = Auth::user()->pembimbingInstansi;
        $pesertaBimbingan = $pembimbing->peserta()->with('user')->get();

        return view('pembimbing.instansi.dashboard', compact('pembimbing', 'pesertaBimbingan'));
    }
}