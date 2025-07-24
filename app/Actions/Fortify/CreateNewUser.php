<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Peserta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            
            // --- PERBAIKAN UTAMA DI SINI ---
            // Secara eksplisit tambahkan 'role' => 'peserta' saat membuat user.
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role' => 'peserta', // <-- INI ADALAH KUNCINYA
            ]);
            
            // Sekarang, objek $user di memori sudah pasti memiliki $user->role == 'peserta'
            // sehingga kondisi 'if' di bawah ini akan berhasil.
            if ($user->role == 'peserta') {
                $user->peserta()->create([
                    'nama' => $user->name,
                    'status' => 'registrasi',
                    'pembimbing_instansi_id' => null,
                    'pembimbing_kominfo_id' => null,
                    'nisn' => null,
                    'gambar' => 'default.png',
                    'asal_sekolah' => 'BELUM DIISI',
                    'kelas' => 'BELUM DIISI',
                    'jurusan' => 'BELUM DIISI',
                    'telepon' => 'BELUM DIISI',
                    'lahir' => now()->toDateString(),
                    'alamat' => 'BELUM DIISI',
                    'mulai_magang' => now()->toDateString(),
                    'selesai_magang' => now()->toDateString(),
                ]);
            }
            
            return $user;
        });
    }
}