<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController; // Tambahkan ini
use App\Http\Controllers\AbsenController; // Tambahkan ini
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE UNTUK ABSENSI (Mungkin publik atau butuh login, sesuaikan) ---
// Form absen biasanya diakses siapa saja atau di device tertentu
Route::post('/absen/store', [AbsenController::class, 'store'])->name('absen.store');


// --- ROUTE YANG BUTUH LOGIN (Middleware Auth) ---
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    // Pengelolaan Data Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/absen/export', [AbsenController::class, 'exportExcel'])->name('absen.export');

    // Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
