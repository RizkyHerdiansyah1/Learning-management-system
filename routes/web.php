<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    // Browse & Enroll Kelas
    Route::get('/browse-kelas', [KelasController::class, 'browse'])->name('kelas.browse');
    Route::post('/kelas/{kelas}/enroll', [KelasController::class, 'enroll'])->name('kelas.enroll');

    // Kelas Detail
    Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('kelas.show');

    // View Materi
    Route::get('/materi/{materi}', [MateriController::class, 'view'])->name('materi.view');
    Route::post('/materi/{materi}/complete', [MateriController::class, 'markComplete'])->name('materi.complete');

    // Quiz
    Route::post('/materi/{materi}/submit-quiz', [MateriController::class, 'submitQuiz'])->name('materi.submit-quiz');
    Route::get('/materi/{materi}/hasil-quiz', [MateriController::class, 'hasilQuiz'])->name('materi.hasil-quiz');
});

/*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])->group(function () {
    // Kelas CRUD
    Route::get('/tambah-kelas', [KelasController::class, 'create'])->name('kelas.create');
    Route::post('/tambah-kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // Kelola Materi
    Route::get('/kelas/{kelas}/kelola-materi', [KelasController::class, 'kelolaMateri'])->name('kelas.kelola-materi');

    // Materi CRUD
    Route::get('/kelas/{kelas}/tambah-materi', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/kelas/{kelas}/tambah-materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');

    // Quiz Soal
    Route::get('/materi/{materi}/tambah-soal', [MateriController::class, 'tambahSoal'])->name('materi.tambah-soal');
    Route::post('/materi/{materi}/tambah-soal', [MateriController::class, 'storeSoal'])->name('materi.store-soal');
    Route::delete('/soal/{kuis}', [MateriController::class, 'destroySoal'])->name('soal.destroy');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (from Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
