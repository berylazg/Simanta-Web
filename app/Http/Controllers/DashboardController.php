<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\AktivitasLog;
use App\Models\Reminder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik utama ──────────────────────────────────────
        $totalTagihan     = Tagihan::count();
        $belumDibayar     = Tagihan::whereIn('status', ['upcoming', 'overdue', 'draft'])->count();
        $terlambat        = Tagihan::where('status', 'overdue')->count();
        $lunas            = Tagihan::where('status', 'paid')->count();
        $reminderTerkirim = Reminder::where('status_kirim', 'terkirim')->count();
        // Jatuh tempo minggu ini
        $jatuhTempoMingguIni = Tagihan::whereIn('status', ['upcoming', 'overdue'])
            ->whereBetween('tanggal_jatuh_tempo', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();

        // Total nilai semua tagihan aktif (belum bayar)
        $totalNilaiTagihan = Tagihan::whereIn('status', ['upcoming', 'overdue', 'draft'])
            ->sum('nominal');

        // ── Tagihan mendatang & terlambat (untuk tabel dashboard) ─
        $tagihanPenting = Tagihan::with(['vendor', 'kategori', 'reminders'])
            ->whereIn('status', ['upcoming', 'overdue'])
            ->orderByRaw("CASE WHEN status = 'overdue' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(10)
            ->get();

        // ── Aktivitas terbaru ────────────────────────────────────
        $aktivitasTerbaru = AktivitasLog::orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // ── Data chart: pengeluaran per bulan (6 bulan terakhir) ─
        $bulanList = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanList[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'paid'  => Tagihan::where('status', 'paid')
                    ->whereYear('tanggal_jatuh_tempo',  $bulan->year)
                    ->whereMonth('tanggal_jatuh_tempo', $bulan->month)
                    ->sum('nominal'),
                'unpaid' => Tagihan::whereIn('status', ['upcoming', 'overdue'])
                    ->whereYear('tanggal_jatuh_tempo',  $bulan->year)
                    ->whereMonth('tanggal_jatuh_tempo', $bulan->month)
                    ->sum('nominal'),
            ];
        }

        // ── Data chart: distribusi status ────────────────────────
        $statusDistribusi = [
            'paid'     => $lunas,
            'upcoming' => Tagihan::where('status', 'upcoming')->count(),
            'overdue'  => $terlambat,
            'draft'    => Tagihan::where('status', 'draft')->count(),
        ];

        return view('dashboard', compact(
            'totalTagihan',
            'belumDibayar',
            'terlambat',
            'lunas',
            'reminderTerkirim',
            'jatuhTempoMingguIni',
            'totalNilaiTagihan',
            'tagihanPenting',
            'aktivitasTerbaru',
            'bulanList',
            'statusDistribusi'
        ));
    }
}
