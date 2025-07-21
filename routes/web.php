<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controller Imports
|--------------------------------------------------------------------------
|
| Mengimpor semua controller yang akan digunakan dalam file routing ini.
| Penggunaan alias (as) membantu menghindari konflik nama.
|
*/

// Dashboard Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Pembimbing\Instansi\DashboardController as PembimbingInstansiDashboardController;
use App\Http\Controllers\Pembimbing\Kominfo\DashboardController as PembimbingKominfoDashboardController;

// Resource Controllers
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PembimbingInstansiController;
use App\Http\Controllers\PembimbingKominfoController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinCutiController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\BeritaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama yang dapat diakses publik

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


/**
 * PINTU GERBANG SETELAH LOGIN
 * Route /dashboard utama ini akan memeriksa peran pengguna dan
 * mengarahkan (redirect) mereka ke dashboard yang sesuai.
 */
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
            return redirect('/'); // Fallback jika peran tidak ada
    }
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Grup Route Berdasarkan Peran Pengguna
|--------------------------------------------------------------------------
|
| Semua route di bawah ini memerlukan pengguna untuk login terlebih dahulu.
| Setiap grup dilindungi oleh prefix dan nama route untuk kerapian,
| dan idealnya ditambahkan middleware 'role' untuk keamanan.
|
*/
Route::middleware(['auth'])->group(function () {

    // === GRUP ROUTE UNTUK ADMIN ===
    Route::prefix('admin')->name('admin.')->group(function () {
        // ->middleware('role:admin') // <- Tambahkan middleware peran di sini

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('peserta', PesertaController::class);
        Route::resource('pembimbing-instansi', PembimbingInstansiController::class);
        Route::resource('pembimbing-kominfo', PembimbingKominfoController::class);
        Route::resource('ruangan', RuanganController::class);
        Route::resource('berita', BeritaController::class);
        Route::resource('surat-masuk', SuratMasukController::class);
    });

    // === GRUP ROUTE UNTUK PESERTA ===
    Route::prefix('peserta')->name('peserta.')->group(function () {
        // ->middleware('role:peserta') // <- Tambahkan middleware peran di sini

        Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');

        Route::resource('kegiatan', KegiatanController::class);
        Route::resource('absensi', AbsensiController::class);
        Route::resource('izin-cuti', IzinCutiController::class);
    });

    // === GRUP ROUTE UNTUK PEMBIMBING INSTANSI ===
    Route::prefix('pembimbing-instansi')->name('pembimbing.instansi.')->group(function () {
        // ->middleware('role:pembimbing_instansi') // <- Tambahkan middleware peran di sini

        Route::get('/dashboard', [PembimbingInstansiDashboardController::class, 'index'])->name('dashboard');
        // Route untuk melihat daftar peserta bimbingan
        Route::get('/bimbingan', [PembimbingInstansiController::class, 'indexBimbingan'])->name('bimbingan.index');
        Route::get('/bimbingan/{peserta}', [PembimbingInstansiController::class, 'showBimbingan'])->name('bimbingan.show');
    });

    // === GRUP ROUTE UNTUK PEMBIMBING KOMINFO ===
    Route::prefix('pembimbing-kominfo')->name('pembimbing.kominfo.')->group(function () {
        // ->middleware('role:pembimbing_kominfo') // <- Tambahkan middleware peran di sini

        Route::get('/dashboard', [PembimbingKominfoDashboardController::class, 'index'])->name('dashboard');
         // Route untuk melihat daftar peserta bimbingan
        Route::get('/bimbingan', [PembimbingKominfoController::class, 'indexBimbingan'])->name('bimbingan.index');
        Route::get('/bimbingan/{peserta}', [PembimbingKominfoController::class, 'showBimbingan'])->name('bimbingan.show');
    });

});