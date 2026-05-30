<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\RingkasanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PengaturanController;

Route::get('/', function () {
    return view('welcome');
});

// Route Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard/realtime', [AdminDashboardController::class, 'realtimeData'])->name('admin.dashboard.realtime');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('admin.users.toggle');
});

// Route User
Route::middleware(['auth', 'check.active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('materi', MateriController::class)->except(['edit', 'update']);
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{kuis}', [KuisController::class, 'show'])->name('kuis.show');
    Route::post('/kuis/{kuis}/submit', [KuisController::class, 'submit'])->name('kuis.submit');
    Route::get('/kuis/{kuis}/result/{hasil}', [KuisController::class, 'result'])->name('kuis.result');
    Route::get('/ringkasan', [RingkasanController::class, 'index'])->name('ringkasan.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::patch('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::delete('/pengaturan/account', [PengaturanController::class, 'destroyAccount'])->name('pengaturan.destroy');
});

// Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/riwayat/materi/{materi}', [RiwayatController::class, 'destroyMateri'])->name('riwayat.destroyMateri');
    Route::delete('/riwayat/kuis/{hasil}', [RiwayatController::class, 'destroyKuis'])->name('riwayat.destroyKuis');
    Route::post('/pengaturan/verify-password', [PengaturanController::class, 'verifyPassword'])->name('pengaturan.verify');
});

require __DIR__.'/auth.php';