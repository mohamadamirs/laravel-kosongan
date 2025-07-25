<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil statis untuk pengguna yang login.
     * (Halaman "About Me" yang cantik).
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('peserta.kegiatan', 'peserta.absensi', 'peserta.izinCuti', 'pembimbingInstansi.peserta', 'pembimbingKominfo.peserta');

        // Menyiapkan data counter dinamis berdasarkan peran
        $counterData = [];
        if ($user->role == 'peserta' && $user->peserta) {
            $selesaiMagang = \Carbon\Carbon::parse($user->peserta->selesai_magang);

            // --- PERBAIKAN DI SINI ---
            // Gunakan fungsi ceil() dari PHP untuk membulatkan ke atas.
            // Jika hari ini tanggal 23 dan selesai tanggal 25, diffInDays akan menghasilkan 2.
            // Tetapi jika dihitung per jam, bisa jadi 1.9 sekian. ceil() akan membulatkannya menjadi 2.
            $hariTersisa = ceil(now()->diffInSeconds($selesaiMagang) / (60 * 60 * 24));

            $counterData = [
                ['label' => 'Total Kegiatan', 'value' => $user->peserta->kegiatan->count()],
                ['label' => 'Total Absensi', 'value' => $user->peserta->absensi->count()],
                ['label' => 'Total Izin', 'value' => $user->peserta->izinCuti->count()],
                // Pastikan tidak menampilkan angka negatif jika sudah lewat
                ['label' => 'Hari Tersisa', 'value' => $hariTersisa > 0 ? $hariTersisa : 0],
            ];
        }

        return view('profile.show', compact('user', 'counterData'));
    }

    /**
     * Menampilkan form untuk mengedit profil pengguna yang login.
     */
    public function edit()
    {
        $user = Auth::user();
        // Eager load semua kemungkinan relasi profil agar data tersedia di form
        $user->load('peserta', 'pembimbingInstansi', 'pembimbingKominfo');

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui profil pengguna yang login berdasarkan perannya.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Aturan validasi dasar yang berlaku untuk semua peran
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];

        // 2. Tambahkan aturan validasi kondisional berdasarkan peran
        if ($user->role == 'peserta' && $user->peserta) {
            $rules += [
                'nisn' => ['required', 'string', 'max:20', Rule::unique('peserta', 'nisn')->ignore($user->peserta->id)],
                'asal_sekolah' => 'required|string|max:255',
                'kelas' => 'required|string|max:50',
                'jurusan' => 'required|string|max:100',
                'telepon' => 'required|string|max:15',
                'lahir' => 'required|date',
                'alamat' => 'required|string',
                'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi foto untuk peserta
            ];
        }

        if (in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo'])) {
            $rules += [
                'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'telepon' => ['required', 'string', 'max:15'],
                'bidang' => ['required', 'string', 'max:255'],
            ];
        }

        $request->validate($rules);

        // 3. Gunakan transaksi untuk update data
        DB::transaction(function () use ($request, $user) {
            // Update data di tabel 'users'
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Update data di tabel profil yang sesuai
            switch ($user->role) {
                case 'peserta':
                    if ($user->peserta) {
                        $profileData = $request->only(['nisn', 'asal_sekolah', 'kelas', 'jurusan', 'telepon', 'lahir', 'alamat']);

                        // Handle upload foto profil untuk peserta
                        if ($request->hasFile('foto')) {
                            if ($user->peserta->gambar && $user->peserta->gambar !== 'default.png') {
                                Storage::disk('public')->delete('peserta/fotos/' . $user->peserta->gambar);
                            }
                            $path = $request->file('foto')->store('peserta/fotos', 'public');
                            $profileData['gambar'] = basename($path);
                        }
                        $user->peserta->update($profileData);
                    }
                    break;

                case 'pembimbing_instansi':
                case 'pembimbing_kominfo':
                    $profile = $user->pembimbingInstansi ?? $user->pembimbingKominfo;
                    if ($profile) {
                        $profileData = $request->only(['telepon', 'bidang']);
                        if ($request->hasFile('foto')) {
                            if ($profile->foto) {
                                Storage::disk('public')->delete('avatars/' . $profile->foto);
                            }
                            $path = $request->file('foto')->store('avatars', 'public');
                            $profileData['foto'] = basename($path);
                        }
                        $profile->update($profileData);
                    }
                    break;
            }
        });

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
