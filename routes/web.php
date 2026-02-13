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


use App\Http\Controllers\NotificationController;


Route::get("/", function () {
    return redirect()->route("login");
})->name('home');
Route::middleware(['auth'])->group(function () {


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
        Route::post('/judul-ta/{id}/re-submit', [JudulTAController::class, 'reSubmitAfterConsultation'])->name('mahasiswa.judul-ta.re-submit');


        Route::get('surat/{id}', [SuratController::class, 'show'])->name('mahasiswa.surat.show');
        Route::get('surat/{id}/download', [SuratController::class, 'downloadPDF'])->name('mahasiswa.surat.download');

        Route::get('thesis', [App\Http\Controllers\Mahasiswa\ThesisArchiveController::class, 'index'])->name('mahasiswa.thesis.index');
        Route::get('thesis/{id}', [App\Http\Controllers\Mahasiswa\ThesisArchiveController::class, 'show'])->name('mahasiswa.thesis.show');

        Route::get('plagiarism-check', [App\Http\Controllers\Mahasiswa\ThesisArchiveController::class, 'plagiarismCheck'])->name('mahasiswa.plagiarism.check');
        Route::post('plagiarism-check', [App\Http\Controllers\Mahasiswa\ThesisArchiveController::class, 'checkPlagiarism'])->name('mahasiswa.plagiarism.submit');
    });

    // Kajur routes
    Route::prefix('kajur')->middleware(['role:kajur'])->group(function () {
        Route::get('judul-ta', [KajurJudulTAController::class, 'index'])->name('kajur.judul-ta.index');
        Route::get('judul-ta/{id}', [KajurJudulTAController::class, 'show'])->name('kajur.judul-ta.show');
        #Route::post('judul-ta/{id}/approve', [KajurJudulTAController::class, 'approve'])->name('kajur.judul-ta.approve');
        // Route::post('judul-ta/{id}/reject', [KajurJudulTAController::class, 'reject'])->name('kajur.judul-ta.reject');
        Route::post('judul-ta/{id}/assign-pembimbing', [KajurJudulTAController::class, 'assignPembimbing'])->name('kajur.judul-ta.assignPembimbing');
        Route::post('judul-ta/{id}/assign', [KajurJudulTAController::class, 'assignPembimbing'])->name('kajur.judul-ta.assignPembimbing');
        Route::put('judul-ta/{id}/finalize', [KajurJudulTAController::class, 'finalize'])->name('kajur.judul-ta.finalize');
        Route::post('judul-ta/{id}/process-submission', [KajurJudulTAController::class, 'processSubmission'])->name('kajur.judul-ta.processSubmission');
    });

    // Dosen routes
    Route::prefix('dosen')->middleware(['role:dosen'])->group(function () {
        Route::get('bimbingan/{id}', [BimbinganController::class, 'show'])->name('dosen.bimbingan.show');
        Route::get('bimbingan/create', [BimbinganController::class, 'create'])->name('dosen.bimbingan.create');
        Route::get('bimbingan', [BimbinganController::class, 'index'])->name('dosen.bimbingan.index');
        Route::post('revisi/{id}', [DosenRevisiController::class, 'store'])->name('dosen.revisi.store');
        //Route::post('bimbingan/{id}/finalize', [BimbinganController::class, 'finalize'])->name('dosen.bimbingan.finalize');
        Route::post('/dosen/bimbingan/{id}/process-resubmission', [BimbinganController::class, 'processReSubmittedTitle'])->name('dosen.bimbingan.process-resubmission');
        Route::post('/dosen/bimbingan/{id}/process-initial-review', [BimbinganController::class, 'processInitialLecturerReview'])->name('dosen.bimbingan.process-initial-review');
    });

    Route::get('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notification.read');
});

require __DIR__ . '/auth.php';
