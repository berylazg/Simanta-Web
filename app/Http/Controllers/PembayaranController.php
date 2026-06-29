<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\AktivitasLog;

class PembayaranController extends Controller
{
    public function index()
    {
        // Hanya tampilkan tagihan yang belum dibayar
        $tagihans = Tagihan::with(['vendor', 'kategori'])
            ->whereIn('status', ['upcoming', 'overdue', 'draft'])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        return view('pembayaran.index', compact('tagihans'));
    }

    public function getTagihan($id)
    {
        $tagihan = Tagihan::with(['vendor', 'kategori', 'reminders'])->findOrFail($id);
        return response()->json($tagihan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tagihan_id'     => 'required|exists:tagihans,id',
            'tanggal_bayar'  => 'required|date',
            'metode_bayar'   => 'required',
            'jumlah_bayar'   => 'required|numeric|min:0',
        ], [
            'tagihan_id.required'    => 'Pilih tagihan terlebih dahulu.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'metode_bayar.required'  => 'Metode pembayaran wajib dipilih.',
            'jumlah_bayar.required'  => 'Jumlah bayar wajib diisi.',
        ]);

        // Simpan pembayaran
        Pembayaran::create([
            'tagihan_id'      => $request->tagihan_id,
            'user_id'         => auth()->id(),
            'tanggal_bayar'   => $request->tanggal_bayar,
            'jumlah_bayar'    => $request->jumlah_bayar,
            'metode_bayar'    => $request->metode_bayar,
            'nomor_referensi' => $request->nomor_referensi,
            'catatan'         => $request->catatan,
        ]);

        // Update status tagihan jadi paid
        $tagihan = Tagihan::findOrFail($request->tagihan_id);
        $tagihan->update(['status' => 'paid']);

        // Catat aktivitas
        AktivitasLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'Mencatat Pembayaran',
            'model_tipe' => 'tagihans',
            'model_id'   => $tagihan->id,
            'keterangan' => 'Invoice '.$tagihan->nomor_invoice.' telah dicatat pembayarannya',
        ]);

        return redirect()->route('pembayaran.index')
                         ->with('success', 'Pembayaran '.$tagihan->nomor_invoice.' berhasil dicatat! Status diubah menjadi Lunas.');
    }
}
