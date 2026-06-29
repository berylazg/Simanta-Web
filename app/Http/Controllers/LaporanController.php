<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Vendor;
use App\Models\KategoriTagihan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $vendors   = Vendor::orderBy('nama_vendor')->get();
        $kategoris = KategoriTagihan::all();

        $query = Tagihan::query();
        if ($request->bulan)      $query->whereMonth('tanggal_jatuh_tempo', $request->bulan);
        if ($request->tahun)      $query->whereYear('tanggal_jatuh_tempo',  $request->tahun);
        if ($request->vendor_id)  $query->where('vendor_id',  $request->vendor_id);
        if ($request->kategori_id) $query->where('kategori_id', $request->kategori_id);

        $tagihans = $query->with(['vendor','kategori'])->get();

        // Statistik
        $totalTagihan    = $tagihans->count();
        $sudahDibayar    = $tagihans->where('status','paid')->count();
        $belumDibayar    = $tagihans->whereIn('status',['upcoming','overdue','draft'])->count();
        $terlambat       = $tagihans->where('status','overdue')->count();
        $totalPengeluaran = $tagihans->where('status','paid')->sum('nominal');
        $tagihanTerbesar = $tagihans->max('nominal') ?? 0;

        // Chart pengeluaran bulanan (6 bulan terakhir)
        $bulanList = [];
        for ($i = 5; $i >= 0; $i--) {
            $b = Carbon::now()->subMonths($i);
            $q = Tagihan::query();
            if ($request->vendor_id)   $q->where('vendor_id',   $request->vendor_id);
            if ($request->kategori_id) $q->where('kategori_id', $request->kategori_id);
            $bulanList[] = [
                'label' => $b->format('M \'y'),
                'paid'  => (clone $q)->where('status','paid')
                    ->whereYear('tanggal_jatuh_tempo',$b->year)
                    ->whereMonth('tanggal_jatuh_tempo',$b->month)
                    ->sum('nominal'),
                'unpaid'=> (clone $q)->whereIn('status',['upcoming','overdue'])
                    ->whereYear('tanggal_jatuh_tempo',$b->year)
                    ->whereMonth('tanggal_jatuh_tempo',$b->month)
                    ->sum('nominal'),
            ];
        }

        // Chart tren (6 bulan)
        $trenList = $bulanList;

        // Pengeluaran per kategori
        $perKategori = KategoriTagihan::withSum(['tagihans as total' => function($q) use($request) {
            if ($request->vendor_id) $q->where('vendor_id', $request->vendor_id);
        }], 'nominal')->get()->filter(fn($k) => $k->total > 0);

        // Distribusi status
        $statusDist = [
            ['label'=>'Lunas',           'val'=>$tagihans->where('status','paid')->count(),     'color'=>'#10b981'],
            ['label'=>'Akan Jatuh Tempo','val'=>$tagihans->where('status','upcoming')->count(), 'color'=>'#f59e0b'],
            ['label'=>'Terlambat',       'val'=>$tagihans->where('status','overdue')->count(),  'color'=>'#ef4444'],
            ['label'=>'Draft',           'val'=>$tagihans->where('status','draft')->count(),    'color'=>'#3b82f6'],
        ];

        return view('laporan.index', compact(
            'vendors','kategoris','bulanList','trenList',
            'totalTagihan','sudahDibayar','belumDibayar',
            'terlambat','totalPengeluaran','tagihanTerbesar',
            'perKategori','statusDist'
        ));
    }
}
