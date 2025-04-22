<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\JudulTAController;
use App\Http\Controllers\Mahasiswa\RevisiController;
use App\Http\Controllers\Mahasiswa\SuratController;
use App\Http\Controllers\Kajur\JudulTAController as KajurJudulTAController;
use App\Http\Controllers\Dosen\BimbinganController;
use App\Http\Controllers\Dosen\RevisiController as DosenRevisiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mahasiswa routes
    Route::prefix('mahasiswa')->middleware(['role:mahasiswa'])->group(function () {
        Route::get('judul-ta/create', [JudulTAController::class, 'create'])->name('mahasiswa.judul-ta.create');
        Route::get('judul-ta/index', [JudulTAController::class, 'index'])->name('mahasiswa.judul-ta.index');
        Route::get('judul-ta/{id}', [JudulTAController::class, 'show'])->name('mahasiswa.judul-ta.show');
        Route::post('judul-ta', [JudulTAController::class, 'store'])->name('mahasiswa.judul-ta.store');
        Route::get('revisi/{id}', [RevisiController::class, 'show'])->name('mahasiswa.revisi.show');
        Route::post('revisi/{id}', [RevisiController::class, 'store'])->name('mahasiswa.revisi.store');
        Route::get('surat/{id}', [SuratController::class, 'show'])->name('mahasiswa.surat.show');
    });

    // Kajur routes
    Route::prefix('kajur')->middleware(['role:kajur'])->group(function () {
        Route::get('judul-ta', [KajurJudulTAController::class, 'index'])->name('kajur.judul-ta.index');
        Route::get('judul-ta/{id}', [KajurJudulTAController::class, 'show'])->name('kajur.judul-ta.show');
        Route::post('judul-ta/{id}/approve', [KajurJudulTAController::class, 'approve'])->name('kajur.judul-ta.approve');
        Route::post('judul-ta/{id}/reject', [KajurJudulTAController::class, 'reject'])->name('kajur.judul-ta.reject');
        Route::post('judul-ta/{id}/assign-pembimbing', [KajurJudulTAController::class, 'assignPembimbing'])->name('kajur.judul-ta.assign-pembimbing');
    });

    // Dosen routes
    Route::prefix('dosen')->middleware(['role:dosen'])->group(function () {
        Route::get('bimbingan/{id}', [BimbinganController::class, 'show'])->name('dosen.bimbingan.show');
        Route::get('bimbingan/create', [BimbinganController::class, 'create'])->name('dosen.bimbingan.create');
        Route::get('bimbingan', [BimbinganController::class, 'index'])->name('dosen.bimbingan.index');
        Route::post('revisi/{id}', [DosenRevisiController::class, 'store'])->name('dosen.revisi.store');
        Route::post('bimbingan/{id}/finalize', [BimbinganController::class, 'finalize'])->name('dosen.bimbingan.finalize');
    });
});

require __DIR__ . '/auth.php';
