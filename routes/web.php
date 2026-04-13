<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSoalController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;

// ─── Auth Routes ───
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Admin Routes ───
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Guru Management
    Route::get('/guru', [AdminController::class, 'guruIndex'])->name('guru.index');
    Route::get('/guru/create', [AdminController::class, 'guruCreate'])->name('guru.create');
    Route::post('/guru', [AdminController::class, 'guruStore'])->name('guru.store');
    Route::get('/guru/{guru}/edit', [AdminController::class, 'guruEdit'])->name('guru.edit');
    Route::put('/guru/{guru}', [AdminController::class, 'guruUpdate'])->name('guru.update');
    Route::delete('/guru/{guru}', [AdminController::class, 'guruDestroy'])->name('guru.destroy');

    // Siswa Management
    Route::get('/siswa', [AdminController::class, 'siswaIndex'])->name('siswa.index');
    Route::get('/siswa/aktif', [AdminController::class, 'siswaAktifIndex'])->name('siswa.aktif');
    Route::get('/siswa/create', [AdminController::class, 'siswaCreate'])->name('siswa.create');
    Route::post('/siswa', [AdminController::class, 'siswaStore'])->name('siswa.store');
    Route::get('/siswa/import', [AdminController::class, 'siswaImportForm'])->name('siswa.import.form');
    Route::post('/siswa/import', [AdminController::class, 'siswaImport'])->name('siswa.import');
    Route::get('/siswa/template', [AdminController::class, 'siswaTemplate'])->name('siswa.template');
    Route::get('/siswa/{siswa}/edit', [AdminController::class, 'siswaEdit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}', [AdminController::class, 'siswaUpdate'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [AdminController::class, 'siswaDestroy'])->name('siswa.destroy');
    Route::post('/siswa/{siswa}/kick', [AdminController::class, 'siswaKick'])->name('siswa.kick');

    // Ujian Management
    Route::get('/ujian', [AdminController::class, 'ujianIndex'])->name('ujian.index');
    Route::get('/ujian/create', [AdminController::class, 'ujianCreate'])->name('ujian.create');
    Route::post('/ujian', [AdminController::class, 'ujianStore'])->name('ujian.store');
    Route::get('/ujian/{ujian}', [AdminController::class, 'ujianShow'])->name('ujian.show');
    Route::get('/ujian/{ujian}/edit', [AdminController::class, 'ujianEdit'])->name('ujian.edit');
    Route::put('/ujian/{ujian}', [AdminController::class, 'ujianUpdate'])->name('ujian.update');
    Route::post('/ujian/{ujian}/regenerate-token', [AdminController::class, 'ujianRegenerateToken'])->name('ujian.regenerate-token');
    Route::post('/ujian/{ujian}/reset-peserta/{siswa}', [AdminController::class, 'ujianResetPeserta'])->name('ujian.reset-peserta');
    Route::delete('/ujian/{ujian}', [AdminController::class, 'ujianDestroy'])->name('ujian.destroy');

    // Soal Management (Admin)
    Route::get('/soal', [AdminSoalController::class, 'index'])->name('soal.index');
    Route::get('/soal/detail', [AdminSoalController::class, 'detail'])->name('soal.detail');
    Route::post('/soal/paket', [AdminSoalController::class, 'storePaket'])->name('soal.paket.store');
    Route::put('/soal/paket/{paket_soal}', [AdminSoalController::class, 'updatePaket'])->name('soal.paket.update');
    Route::delete('/soal/paket/{paket_soal}', [AdminSoalController::class, 'destroyPaket'])->name('soal.paket.destroy');
    Route::get('/soal/paket/{paket_soal}', [AdminSoalController::class, 'paket'])->name('soal.paket');
    Route::get('/soal/paket/{paket_soal}/import', [AdminSoalController::class, 'importForm'])->name('soal.import.form');
    Route::post('/soal/paket/{paket_soal}/import', [AdminSoalController::class, 'import'])->name('soal.import');
    Route::get('/soal/template', [AdminSoalController::class, 'template'])->name('soal.template');
    Route::get('/soal/paket/{paket_soal}/create', [AdminSoalController::class, 'create'])->name('soal.create');
    Route::post('/soal', [AdminSoalController::class, 'store'])->name('soal.store');
    Route::get('/soal/{soal}/edit', [AdminSoalController::class, 'edit'])->name('soal.edit');
    Route::put('/soal/{soal}', [AdminSoalController::class, 'update'])->name('soal.update');
    Route::delete('/soal/paket/{paket_soal}/empty', [AdminSoalController::class, 'empty'])->name('soal.empty');
    Route::delete('/soal/{soal}', [AdminSoalController::class, 'destroy'])->name('soal.destroy');

    // Hasil Management (Admin)
    Route::get('/hasil', [\App\Http\Controllers\AdminHasilController::class, 'index'])->name('hasil.index');
    Route::get('/hasil/{hasil_ujian}/edit', [\App\Http\Controllers\AdminHasilController::class, 'edit'])->name('hasil.edit');
    Route::put('/hasil/{hasil_ujian}', [\App\Http\Controllers\AdminHasilController::class, 'update'])->name('hasil.update');
    Route::delete('/hasil/{hasil_ujian}', [\App\Http\Controllers\AdminHasilController::class, 'destroy'])->name('hasil.destroy');

    // Kelas Management (Admin)
    Route::get('/kelas', [\App\Http\Controllers\AdminKelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [\App\Http\Controllers\AdminKelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [\App\Http\Controllers\AdminKelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{kelas}/edit', [\App\Http\Controllers\AdminKelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [\App\Http\Controllers\AdminKelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [\App\Http\Controllers\AdminKelasController::class, 'destroy'])->name('kelas.destroy');

    // Mapel Management
    Route::get('/mapel', [\App\Http\Controllers\AdminMapelController::class, 'index'])->name('mapel.index');
    Route::get('/mapel/create', [\App\Http\Controllers\AdminMapelController::class, 'create'])->name('mapel.create');
    Route::post('/mapel', [\App\Http\Controllers\AdminMapelController::class, 'store'])->name('mapel.store');
    Route::get('/mapel/{mapel}/edit', [\App\Http\Controllers\AdminMapelController::class, 'edit'])->name('mapel.edit');
    Route::put('/mapel/{mapel}', [\App\Http\Controllers\AdminMapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/{mapel}', [\App\Http\Controllers\AdminMapelController::class, 'destroy'])->name('mapel.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/update-system', [\App\Http\Controllers\AdminSettingController::class, 'updateSystem'])->name('settings.update-system');
    Route::post('/settings/clear-cache', [\App\Http\Controllers\AdminSettingController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/clear-config', [\App\Http\Controllers\AdminSettingController::class, 'clearConfig'])->name('settings.clear-config');
    Route::post('/settings/link-storage', [\App\Http\Controllers\AdminSettingController::class, 'linkStorage'])->name('settings.link-storage');

    // SEB (Safe Exam Browser) Settings
    Route::get('/settings/seb', [\App\Http\Controllers\AdminSettingController::class, 'sebIndex'])->name('settings.seb');
    Route::put('/settings/seb', [\App\Http\Controllers\AdminSettingController::class, 'sebUpdate'])->name('settings.seb.update');
});

// ─── Guru Routes ───
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');

    // Soal Management
    Route::get('/soal', [GuruController::class, 'soalIndex'])->name('soal.index');
    Route::get('/soal/detail', [GuruController::class, 'soalDetail'])->name('soal.detail');
    Route::post('/soal/paket', [GuruController::class, 'soalStorePaket'])->name('soal.paket.store');
    Route::put('/soal/paket/{paket_soal}', [GuruController::class, 'soalUpdatePaket'])->name('soal.paket.update');
    Route::delete('/soal/paket/{paket_soal}', [GuruController::class, 'soalDestroyPaket'])->name('soal.paket.destroy');
    Route::get('/soal/paket/{paket_soal}', [GuruController::class, 'soalPaket'])->name('soal.paket');
    Route::get('/soal/paket/{paket_soal}/import', [GuruController::class, 'soalImportForm'])->name('soal.import.form');
    Route::post('/soal/paket/{paket_soal}/import', [GuruController::class, 'soalImport'])->name('soal.import');
    Route::get('/soal/template', [GuruController::class, 'soalTemplate'])->name('soal.template');
    Route::get('/soal/paket/{paket_soal}/create', [GuruController::class, 'soalCreate'])->name('soal.create');
    Route::post('/soal', [GuruController::class, 'soalStore'])->name('soal.store');
    Route::get('/soal/{soal}/edit', [GuruController::class, 'soalEdit'])->name('soal.edit');
    Route::put('/soal/{soal}', [GuruController::class, 'soalUpdate'])->name('soal.update');
    Route::delete('/soal/paket/{paket_soal}/empty', [GuruController::class, 'soalEmpty'])->name('soal.empty');
    Route::delete('/soal/{soal}', [GuruController::class, 'soalDestroy'])->name('soal.destroy');

    // Ujian Management (Guru)
    Route::get('/ujian', [GuruController::class, 'ujianIndex'])->name('ujian.index');
    Route::get('/ujian/create', [GuruController::class, 'ujianCreate'])->name('ujian.create');
    Route::post('/ujian', [GuruController::class, 'ujianStore'])->name('ujian.store');
    Route::get('/ujian/{ujian}', [GuruController::class, 'ujianShow'])->name('ujian.show');
    Route::get('/ujian/{ujian}/edit', [GuruController::class, 'ujianEdit'])->name('ujian.edit');
    Route::put('/ujian/{ujian}', [GuruController::class, 'ujianUpdate'])->name('ujian.update');
    Route::post('/ujian/{ujian}/regenerate-token', [GuruController::class, 'ujianRegenerateToken'])->name('ujian.regenerate-token');
    Route::post('/ujian/{ujian}/reset-peserta/{siswa}', [GuruController::class, 'ujianResetPeserta'])->name('ujian.reset-peserta');
    Route::delete('/ujian/{ujian}', [GuruController::class, 'ujianDestroy'])->name('ujian.destroy');

    // Hasil Ujian (View Only)
    Route::get('/hasil', [GuruController::class, 'hasilIndex'])->name('hasil.index');
});

// ─── Siswa Routes ───
Route::prefix('siswa')->name('siswa.')->middleware('siswa')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/hasil/{hasil}', [SiswaController::class, 'hasil'])->name('hasil');

    // Exam routes — protected by SEB when enabled
    Route::middleware('seb')->group(function () {
        Route::get('/konfirmasi', [SiswaController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/validate-token', [SiswaController::class, 'validateToken'])->name('validate-token');
        Route::get('/ujian/{ujian}/konfirmasi', [SiswaController::class, 'ujianKonfirmasi'])->name('ujian.konfirmasi');
        Route::get('/ujian/{ujian}', [SiswaController::class, 'ujian'])->name('ujian');
        Route::post('/ujian/{ujian}/submit', [SiswaController::class, 'submitUjian'])->name('ujian.submit');
        Route::post('/ujian/{ujian}/save-answer', [SiswaController::class, 'saveAnswer'])->name('ujian.save-answer');
    });
});
