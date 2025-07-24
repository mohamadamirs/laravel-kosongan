<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePembimbingIsAssigned
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        // Cek hanya jika pengguna adalah peserta dan profilnya ada
        if ($user->role == 'peserta' && $peserta) {
            
            // Kondisi terkunci: jika salah satu ID pembimbing KOSONG (null)
            $isLocked = !$peserta->pembimbing_instansi_id || !$peserta->pembimbing_kominfo_id;

            // Jika terkunci DAN mencoba mengakses halaman selain dashboard
            if ($isLocked && !$request->routeIs('peserta.dashboard')) {
                // Paksa kembali ke dashboard dengan pesan peringatan
                return redirect()->route('peserta.dashboard')
                                 ->with('warning', 'Anda belum memiliki pembimbing. Harap tunggu penempatan dari Admin untuk mengakses fitur ini.');
            }
        }

        // Jika semua syarat terpenuhi, izinkan akses
        return $next($request);
    }
}