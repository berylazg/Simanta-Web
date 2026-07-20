<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TagihanController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/tagihan', [App\Http\Controllers\TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan', [App\Http\Controllers\TagihanController::class, 'store'])->name('tagihan.store');
    Route::put('/tagihan/{tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    Route::delete('/tagihan/{tagihan}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class,'update'])->name('pengaturan.update');
    Route::post('/pengaturan/test-email', [PengaturanController::class, 'testEmail'])->name('pengaturan.testEmail');

    // Notifikasi
    Route::get('/notifikasi/data', [NotifikasiController::class, 'data'])->name('notifikasi.data');
    Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.dibaca');
    Route::post('/notifikasi/dibaca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.dibacaSemua');

});

require __DIR__.'/auth.php';

// Keep all imports at the bottom clean
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/setup-db', function() {
    try {
        // 1. Run migrations first
        Artisan::call('migrate', ['--force' => true]);
        $migrationOutput = Artisan::output();

        // 2. If a local simanta.sql file exists, read and execute it to force create missing tables
        $sqlExecuted = 'No SQL file executed';
        if (file_exists(base_path('simanta.sql'))) {
            $sql = file_get_contents(base_path('simanta.sql'));
            
            // Basic safety check: Only execute if tables like 'users' are missing
            $checkUsers = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_name='users'");
            if (empty($checkUsers)) {
                DB::unprepared($sql);
                $sqlExecuted = 'simanta.sql executed successfully!';
            } else {
                $sqlExecuted = 'simanta.sql skipped because tables already exist.';
            }
        }

        // 3. Check what tables exist after both attempts
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
        $tableNames = array_column($tables, 'table_name');

        return response()->json([
            'status' => 'Execution complete',
            'migration_output' => trim($migrationOutput),
            'sql_file_status' => $sqlExecuted,
            'current_database_tables' => $tableNames,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Failed',
            'error_message' => $e->getMessage()
        ]);
    }
});
