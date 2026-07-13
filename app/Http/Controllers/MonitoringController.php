<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\KategoriTagihan;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Statistik kartu atas
        $total        = Tagihan::count();
        $sudahDibayar = Tagihan::where('status', 'paid')->count();
        $belumDibayar = Tagihan::whereIn('status', ['upcoming', 'overdue', 'draft'])->count();
        $akanJatuhTempo = Tagihan::where('status', 'upcoming')->count();
        $terlambat    = Tagihan::where('status', 'overdue')->count();

        // Query utama dengan filter
        $query = Tagihan::with(['kategori', 'reminders']);

        // Filter search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_invoice', 'like', '%'.$request->search.'%')
                  ->orWhere('nama_tagihan', 'like', '%'.$request->search.'%');
            });
        }

        // Filter vendor
        if ($request->nama_vendor) {
            $query->where('nama_vendor', $request->nama_vendor);
        }

        // Filter kategori
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tagihans  = $query->orderBy('tanggal_jatuh_tempo', 'asc')->get();
        $vendors = Tagihan::select('nama_vendor')
            ->whereNotNull('nama_vendor')
            ->where('nama_vendor', '!=', '')
            ->distinct()
            ->orderBy('nama_vendor')
            ->pluck('nama_vendor');
        $kategoris = KategoriTagihan::all();

        return view('monitoring.index', compact(
            'total', 'sudahDibayar', 'belumDibayar',
            'akanJatuhTempo', 'terlambat',
            'tagihans', 'vendors', 'kategoris'
        ));
    }
}
