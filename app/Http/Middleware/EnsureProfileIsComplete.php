<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek jika user adalah peserta
        if ($user->role == 'peserta') {
            
            // --- PERBAIKAN UTAMA DI SINI ---
            // Cek DUA kondisi:
            // 1. Apakah relasi 'peserta' TIDAK ADA (null)?
            // 2. ATAU jika relasi ada, apakah statusnya BUKAN 'aktif'?
            if (!$user->peserta || $user->peserta->status != 'aktif') {
                
                // Jika salah satu kondisi di atas terpenuhi, dan user mencoba mengakses
                // halaman selain halaman kelengkapan data, paksa redirect.
                if (!$request->routeIs('peserta.lengkapi-data.*')) {
                    return redirect()->route('peserta.lengkapi-data.index')
                                     ->with('info', 'Harap lengkapi data diri dan ajukan permohonan Anda terlebih dahulu.');
                }
            }
        }

        // Jika semua kondisi aman, izinkan akses
        return $next($request);
    }
}