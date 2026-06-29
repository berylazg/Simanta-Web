<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Vendor;
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
        $query = Tagihan::with(['vendor', 'kategori', 'reminders']);

        // Filter search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_invoice', 'like', '%'.$request->search.'%')
                  ->orWhere('nama_tagihan', 'like', '%'.$request->search.'%');
            });
        }

        // Filter vendor
        if ($request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
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
        $vendors   = Vendor::orderBy('nama_vendor')->get();
        $kategoris = KategoriTagihan::all();

        return view('monitoring.index', compact(
            'total', 'sudahDibayar', 'belumDibayar',
            'akanJatuhTempo', 'terlambat',
            'tagihans', 'vendors', 'kategoris'
        ));
    }
}
