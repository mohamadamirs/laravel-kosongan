<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika pengguna tidak login, biarkan middleware 'auth' yang menanganinya
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Loop melalui setiap peran yang diizinkan untuk route ini
        foreach ($roles as $role) {
            // Jika peran pengguna cocok, izinkan akses
            if ($user->role == $role) {
                return $next($request);
            }
        }

        // Jika tidak ada peran yang cocok, tolak akses
        // Mengarahkan ke 403 Forbidden lebih baik daripada redirect loop
        abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI IZIN YANG SESUAI.');
    }
}