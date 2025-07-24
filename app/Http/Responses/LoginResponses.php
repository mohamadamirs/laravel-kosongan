<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        $role = Auth::user()->role;

        // Logika pengalihan berdasarkan peran
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'peserta':
                return redirect()->route('peserta.dashboard');
            case 'pembimbing_instansi':
                return redirect()->route('pembimbing.instansi.dashboard');
            case 'pembimbing_kominfo':
                return redirect()->route('pembimbing.kominfo.dashboard');
            default:
                // Fallback jika peran tidak dikenali, arahkan ke halaman utama
                return redirect('/');
        }
    }
}