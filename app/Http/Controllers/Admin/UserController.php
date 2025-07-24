<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peserta;
use App\Models\PembimbingInstansi;
use App\Models\PembimbingKominfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Eager load relasi untuk efisiensi jika Anda ingin menampilkan data terkait di view
        $users = User::with('peserta', 'pembimbingInstansi', 'pembimbingKominfo')
                     ->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru DAN profil awalnya ke dalam database.
     */
    public function store(Request $request)
    {
        // Definisikan aturan validasi dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'peserta', 'pembimbing_instansi', 'pembimbing_kominfo'])],
        ];

        // Tambahkan aturan validasi kondisional jika peran adalah 'peserta'
        if ($request->role === 'peserta') {
            $rules['mulai_magang'] = ['required', 'date'];
            $rules['selesai_magang'] = ['required', 'date', 'after_or_equal:mulai_magang'];
        }

        // Jalankan validasi
        $request->validate($rules);

        // Gunakan Transaksi Database untuk menjamin konsistensi data
        try {
            DB::transaction(function () use ($request) {
                // Buat record di tabel 'users'
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                // Buat record awal di tabel profil yang sesuai
                switch ($user->role) {
                    case 'peserta':
                        $user->peserta()->create([
                            'nama' => $user->name,
                            'status' => 'registrasi',
                            'pembimbing_instansi_id' => null,
                            'pembimbing_kominfo_id' => null,
                            'mulai_magang' => $request->mulai_magang,
                            'selesai_magang' => $request->selesai_magang,
                            'nisn' => null, // Menggunakan NULL untuk menghindari error UNIQUE constraint
                            'gambar' => 'default.png',
                            'asal_sekolah' => 'BELUM DIISI',
                            'kelas' => 'BELUM DIISI',
                            'jurusan' => 'BELUM DIISI',
                            'telepon' => 'BELUM DIISI',
                            'lahir' => null,
                            'alamat' => 'BELUM DIISI',
                        ]);
                        break;

                    case 'pembimbing_instansi':
                        $user->pembimbingInstansi()->create([
                            'telepon' => 'BELUM DIISI',
                            'bidang' => 'BELUM DIISI',
                        ]);
                        break;

                    case 'pembimbing_kominfo':
                        $user->pembimbingKominfo()->create([
                            'telepon' => 'BELUM DIISI',
                            'bidang' => 'BELUM DIISI',
                        ]);
                        break;
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat pengguna: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun pengguna baru dan profil awalnya berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pengguna.
     */
    public function edit(User $user)
    {
        // Load relasi agar bisa diakses di form edit (misalnya untuk mengambil tanggal magang)
        $user->load('peserta', 'pembimbingInstansi', 'pembimbingKominfo');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna DAN profilnya di dalam database.
     */
    public function update(Request $request, User $user)
    {
        // Definisikan aturan validasi dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'peserta', 'pembimbing_instansi', 'pembimbing_kominfo'])],
        ];

        // Tambahkan aturan validasi kondisional jika user yang diedit adalah peserta
        if ($request->role === 'peserta') {
            $rules['mulai_magang'] = ['required', 'date'];
            $rules['selesai_magang'] = ['required', 'date', 'after_or_equal:mulai_magang'];
        }

        // Jalankan validasi
        $request->validate($rules);

        // Gunakan Transaksi Database
        try {
            DB::transaction(function () use ($request, $user) {
                // Update data di tabel 'users'
                $userData = $request->only(['name', 'email', 'role']);
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);

                // Update profil yang terkait
                if ($user->role === 'peserta' && $user->peserta) {
                    $user->peserta->update([
                        'mulai_magang' => $request->mulai_magang,
                        'selesai_magang' => $request->selesai_magang,
                        // Jika admin bisa mengedit nama, sinkronkan juga ke tabel peserta
                        'nama' => $request->name,
                    ]);
                }
                // (Anda bisa menambahkan logika 'else if' untuk update pembimbing di sini jika perlu)
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     * Aturan cascade on delete di database akan menghapus profil terkait.
     */
    public function destroy(User $user)
    {
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }

     public function showAssignForm(User $user)
    {
        // 1. Pastikan user yang dipilih adalah seorang peserta
        if ($user->role !== 'peserta' || !$user->peserta) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Hanya pengguna dengan peran peserta yang dapat ditugaskan pembimbing.');
        }

        // 2. Ambil semua data pembimbing yang tersedia
        $pembimbingInstansi = PembimbingInstansi::with('user')->get();
        $pembimbingKominfo = PembimbingKominfo::with('user')->get();

        // 3. Kirim data ke view
        return view('admin.users.assign', compact('user', 'pembimbingInstansi', 'pembimbingKominfo'));
    }
     public function assignPembimbing(Request $request, User $user)
    {
        // 1. Pastikan user yang dipilih adalah seorang peserta
        if ($user->role !== 'peserta' || !$user->peserta) {
            abort(403, 'Aksi tidak diizinkan.');
        }
        
        // 2. Validasi input
        $request->validate([
            'pembimbing_instansi_id' => 'required|exists:pembimbing_instansi,id',
            'pembimbing_kominfo_id' => 'required|exists:pembimbing_kominfo,id',
        ]);
        
        // 3. Update profil peserta, bukan user-nya
        $user->peserta->update([
            'pembimbing_instansi_id' => $request->pembimbing_instansi_id,
            'pembimbing_kominfo_id' => $request->pembimbing_kominfo_id,
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('admin.users.index')
                         ->with('success', 'Pembimbing berhasil ditugaskan untuk ' . $user->name);
    }
}