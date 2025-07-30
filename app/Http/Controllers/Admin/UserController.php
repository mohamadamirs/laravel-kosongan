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
use Illuminate\Support\Facades\URL; // <-- Tambahkan ini
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'peserta', 'pembimbing_instansi', 'pembimbing_kominfo'])],
        ];

        if ($request->role === 'peserta') {
            $rules['mulai_magang'] = ['required', 'date'];
            $rules['selesai_magang'] = ['required', 'date', 'after_or_equal:mulai_magang'];
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                switch ($user->role) {
                    case 'peserta':
                        $user->peserta()->create([
                            'nama' => $user->name,
                            'status' => 'registrasi',
                            'pembimbing_instansi_id' => null,
                            'pembimbing_kominfo_id' => null,
                            'mulai_magang' => $request->mulai_magang,
                            'selesai_magang' => $request->selesai_magang,
                            'nisn' => null,
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
                        $user->pembimbingInstansi()->create(['telepon' => 'BELUM DIISI', 'bidang' => 'BELUM DIISI']);
                        break;
                    case 'pembimbing_kominfo':
                        $user->pembimbingKominfo()->create(['telepon' => 'BELUM DIISI', 'bidang' => 'BELUM DIISI']);
                        break;
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat pengguna: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pengguna.
     */
    public function edit(User $user)
    {
        $user->load('peserta');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna DAN profilnya di dalam database.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'peserta', 'pembimbing_instansi', 'pembimbing_kominfo'])],
        ];

        if ($request->role === 'peserta') {
            $rules['mulai_magang'] = ['required', 'date'];
            $rules['selesai_magang'] = ['required', 'date', 'after_or_equal:mulai_magang'];
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $user) {
                $userData = $request->only(['name', 'email', 'role']);
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);

                if ($user->role === 'peserta' && $user->peserta) {
                    $user->peserta->update(['mulai_magang' => $request->mulai_magang, 'selesai_magang' => $request->selesai_magang, 'nama' => $request->name]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk menugaskan pembimbing ke seorang user peserta.
     */
    public function showAssignForm(User $user)
    {
        if ($user->role !== 'peserta' || !$user->peserta) {
            return redirect()->route('admin.users.index')->with('error', 'Hanya pengguna dengan peran peserta yang dapat ditugaskan pembimbing.');
        }
        $pembimbingInstansi = PembimbingInstansi::with('user')->get();
        $pembimbingKominfo = PembimbingKominfo::with('user')->get();
        return view('admin.users.assign', compact('user', 'pembimbingInstansi', 'pembimbingKominfo'));
    }

    /**
     * Memproses dan menyimpan data penugasan pembimbing.
     */
    public function assignPembimbing(Request $request, User $user)
    {
        if ($user->role !== 'peserta' || !$user->peserta) {
            abort(403, 'Aksi tidak diizinkan.');
        }
        $request->validate(['pembimbing_instansi_id' => 'required|exists:pembimbing_instansi,id', 'pembimbing_kominfo_id' => 'required|exists:pembimbing_kominfo,id']);
        $user->peserta->update(['pembimbing_instansi_id' => $request->pembimbing_instansi_id, 'pembimbing_kominfo_id' => $request->pembimbing_kominfo_id]);
        return redirect()->route('admin.users.index')->with('success', 'Pembimbing berhasil ditugaskan untuk ' . $user->name);
    }

    /**
     * Menghasilkan gambar QR code untuk absensi seorang peserta.
     */
    public function generateQrCode(User $user)
    {
        if ($user->role !== 'peserta' || !$user->peserta) {
            abort(404, 'Peserta tidak ditemukan.');
        }

        // URL yang aman dan berbatas waktu tetap sama
        $signedUrl = URL::temporarySignedRoute(
            'absensi.scan',
            now()->addMinutes(1),
            ['peserta' => $user->peserta->id]
        );

        // --- LOGIKA BARU UNTUK MEMBUAT QR CODE DENGAN BACON/BACON-QR-CODE ---
        $renderer = new ImageRenderer(
            new RendererStyle(250), // Ukuran QR Code dalam piksel
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($signedUrl);
        // -----------------------------------------------------------------

        // Kembalikan sebagai respons gambar SVG
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}