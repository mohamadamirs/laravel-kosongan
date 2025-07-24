<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controller Imports
|--------------------------------------------------------------------------
|
| Mengimpor semua controller yang akan digunakan untuk menjaga agar definisi
| route tetap bersih dan mudah dibaca.
|
*/
use App\Http\Controllers\WelcomeController;

// Dashboard Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Pembimbing\Instansi\DashboardController as PembimbingInstansiDashboardController;
use App\Http\Controllers\Pembimbing\Kominfo\DashboardController as PembimbingKominfoDashboardController;

// Admin Resource Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RuanganController as AdminRuanganController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\SuratMasukController as AdminSuratMasukController;

// Peserta Resource Controllers
use App\Http\Controllers\Peserta\LengkapiDataController;
use App\Http\Controllers\Peserta\KegiatanController as PesertaKegiatanController;
use App\Http\Controllers\Peserta\AbsensiController as PesertaAbsensiController;
use App\Http\Controllers\Peserta\IzinCutiController as PesertaIzinCutiController;

/*
|------------------ --------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan semua rute untuk aplikasi Anda.
|
*/

//==========================================================================
// RUTE PUBLIK (Dapat Diakses oleh Semua Pengguna, Termasuk Tamu)
//==========================================================================

Route::get('/', [WelcomeController::class, 'index']);


// Route untuk MENAMPILKAN halaman login.
// Middleware 'guest' memastikan pengguna yang sudah login tidak dapat mengakses halaman ini lagi.
// Proses backend (POST) ditangani oleh Laravel Fortify secara otomatis.
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Catatan: Logika pengalihan setelah login (redirect) tidak ada di sini.
// Logika tersebut telah dipindahkan ke App\Http\Responses\LoginResponse.php
// dan didaftarkan di FortifyServiceProvider untuk praktik yang lebih baik.

Route::get('/dashboard', function () {
    $role = Auth::user()->role;

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
            // Fallback jika peran tidak dikenal
            Auth::logout();
            return redirect('/login')->with('error', 'Peran Anda tidak dikenali.');
    }
})->middleware(['auth'])->name('dashboard');


//==========================================================================
// RUTE KHUSUS ADMIN
//==========================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD untuk manajemen pengguna (akun login)
    Route::resource('users', AdminUserController::class);
    Route::get('users/{user}/assign-pembimbing', [AdminUserController::class, 'showAssignForm'])->name('users.assign.form');
    Route::patch('users/{user}/assign-pembimbing', [AdminUserController::class, 'assignPembimbing'])->name('users.assign.update');
    // CRUD untuk manajemen data master ruangan/bidang
    Route::resource('ruangan', AdminRuanganController::class);

    // CRUD untuk manajemen berita/pengumuman
    // VERSI BARU (Perbaikan)
    Route::resource('berita', AdminBeritaController::class)->parameters(['berita' => 'berita']);
    Route::patch('berita/{berita}/update-status', [AdminBeritaController::class, 'updateStatus'])->name('berita.updateStatus');

    // Manajemen pendaftaran (hanya lihat daftar, detail, dan update status)
    Route::get('surat-masuk', [AdminSuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::patch('surat-masuk/{suratMasuk}/update-status', [AdminSuratMasukController::class, 'updateStatus'])->name('surat-masuk.updateStatus');
    Route::delete('surat-masuk/{suratMasuk}', [AdminSuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
});


//==========================================================================
// RUTE KHUSUS PESERTA
//==========================================================================
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {

    Route::get('/lengkapi-data', [LengkapiDataController::class, 'index'])->name('lengkapi-data.index');
    Route::post('/lengkapi-data', [LengkapiDataController::class, 'store'])->name('lengkapi-data.store');

    Route::get('/menunggu-pembimbing', [PesertaDashboardController::class, 'showWaitingPage'])->name('menunggu-pembimbing');

    // Grup baru untuk dashboard utama yang DILINDUNGI oleh middleware
    Route::middleware(['profile.complete', 'pembimbing.assigned'])->group(function () {
        Route::get('dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
        Route::resource('kegiatan', PesertaKegiatanController::class);
        Route::resource('absensi', PesertaAbsensiController::class)->only(['index', 'store']);
        Route::resource('izin-cuti', PesertaIzinCutiController::class)->only(['index', 'create', 'store', 'show']);
        // Semua route lain milik peserta taruh di sini
    });
});


//==========================================================================
// RUTE KHUSUS PEMBIMBING INSTANSI
//==========================================================================
Route::middleware(['auth', 'role:pembimbing_instansi'])->prefix('pembimbing-instansi')->name('pembimbing.instansi.')->group(function () {

    Route::get('dashboard', [PembimbingInstansiDashboardController::class, 'index'])->name('dashboard');

    // Route untuk memonitor peserta yang dibimbing
    // Anda perlu membuat method ini di PembimbingInstansiDashboardController
    // Route::get('peserta', [PembimbingInstansiDashboardController::class, 'listPeserta'])->name('peserta.index');
    // Route::get('peserta/{peserta}', [PembimbingInstansiDashboardController::class, 'showPeserta'])->name('peserta.show');

});


//==========================================================================
// RUTE KHUSUS PEMBIMBING KOMINFO
//==========================================================================
Route::middleware(['auth', 'role:pembimbing_kominfo'])->prefix('pembimbing-kominfo')->name('pembimbing.kominfo.')->group(function () {

    Route::get('dashboard', [PembimbingKominfoDashboardController::class, 'index'])->name('dashboard');

    // Route untuk memonitor peserta yang dibimbing
    // Anda perlu membuat method ini di PembimbingKominfoDashboardController
    // Route::get('peserta', [PembimbingKominfoDashboardController::class, 'listPeserta'])->name('peserta.index');
    // Route::get('peserta/{peserta}', [PembimbingKominfoDashboardController::class, 'showPeserta'])->name('peserta.show');

});
