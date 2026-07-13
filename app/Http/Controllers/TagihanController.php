<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\KategoriTagihan;
use App\Services\AktivitasService;
use Carbon\Carbon;

class TagihanController extends Controller
{
    /**
     * Halaman Tagihan
     */
    public function index(Request $request)
    {
        $query = Tagihan::with(['kategori']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_invoice', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_tagihan', 'like', '%' . $request->search . '%');
            });
        }

        $tagihans = $query->orderBy('created_at', 'desc')->get();

        $kategoris = KategoriTagihan::all();

        $nextInvoice = 'INV-' . date('Y') . '-' .
            str_pad(Tagihan::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('tagihan.index', compact(
        'tagihans',
        'kategoris',
        'nextInvoice'
        ));
    }

    /**
     * Simpan Tagihan
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_invoice'       => 'required|unique:tagihans,nomor_invoice',
            'nama_tagihan'        => 'required',
            'nama_vendor'         => 'required|string|max:255',
            'kategori_id'         => 'required|exists:kategori_tagihans,id',
            'nominal'             => 'required|numeric|min:0',
            'tanggal_invoice'     => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status'              => 'required',
        ]);

        $tanggalReminder = Carbon::parse($request->tanggal_jatuh_tempo)
            ->subDays(7)
            ->format('Y-m-d');

        $tagihan = Tagihan::create([
            'user_id'             => auth()->id(),
            'nama_vendor'         =>$request->nama_vendor,
            'kategori_id'         => $request->kategori_id,
            'nomor_invoice'       => $request->nomor_invoice,
            'nama_tagihan'        => $request->nama_tagihan,
            'nomor_kontrak'       => $request->nomor_kontrak,
            'nominal'             => $request->nominal,
            'tanggal_invoice'     => $request->tanggal_invoice,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'tanggal_reminder'    => $tanggalReminder,
            'status'              => $request->status,
            'deskripsi'           => $request->deskripsi,
        ]);

        AktivitasService::log(
            'Tambah Tagihan',
            'Tagihan',
            $tagihan->id,
            'Menambahkan tagihan ' . $tagihan->nomor_invoice
        );

        return redirect()
            ->route('tagihan.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    /**
     * Update Tagihan
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'nomor_invoice'       => 'required|unique:tagihans,nomor_invoice,' . $tagihan->id,
            'nama_tagihan'        => 'required',
            'nama_vendor'         => 'required|string|max:255',
            'kategori_id'         => 'required|exists:kategori_tagihans,id',
            'nominal'             => 'required|numeric|min:0',
            'tanggal_invoice'     => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status'              => 'required',
        ]);

        $tanggalReminder = Carbon::parse($request->tanggal_jatuh_tempo)
            ->subDays(7)
            ->format('Y-m-d');

        $tagihan->update([
            'nama_vendor'         =>$request->nama_vendor,
            'kategori_id'         => $request->kategori_id,
            'nomor_invoice'       => $request->nomor_invoice,
            'nama_tagihan'        => $request->nama_tagihan,
            'nomor_kontrak'       => $request->nomor_kontrak,
            'nominal'             => $request->nominal,
            'tanggal_invoice'     => $request->tanggal_invoice,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'tanggal_reminder'    => $tanggalReminder,
            'status'              => $request->status,
            'deskripsi'           => $request->deskripsi,
        ]);

        AktivitasService::log(
            'Edit Tagihan',
            'Tagihan',
            $tagihan->id,
            'Mengubah tagihan ' . $tagihan->nomor_invoice
        );

        return redirect()
            ->route('tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * Hapus Tagihan
     */
    public function destroy(Tagihan $tagihan)
    {
        AktivitasService::log(
            'Hapus Tagihan',
            'Tagihan',
            $tagihan->id,
            'Menghapus tagihan ' . $tagihan->nomor_invoice
        );

        $tagihan->delete();

        return redirect()
            ->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}