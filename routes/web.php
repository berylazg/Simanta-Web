<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/tagihan', [App\Http\Controllers\TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan', [App\Http\Controllers\TagihanController::class, 'store'])->name('tagihan.store');
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class,'update'])->name('pengaturan.update');
    Route::post('/pengaturan/test-email', [PengaturanController::class, 'testEmail'])->name('pengaturan.testEmail');
    Route::put('/tagihan/{tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    Route::delete('/tagihan/{tagihan}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');

    // Notifikasi
    Route::get('/notifikasi/data', [NotifikasiController::class, 'data'])->name('notifikasi.data');
    Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.dibaca');
    Route::post('/notifikasi/dibaca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.dibacaSemua');

    // Reminder
    Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::post('/reminder/kirim/{id}', [ReminderController::class, 'kirimSatu'])->name('reminder.kirimSatu');
    Route::post('/reminder/kirim-semua', [ReminderController::class, 'kirimSemua'])->name('reminder.kirimSemua');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
