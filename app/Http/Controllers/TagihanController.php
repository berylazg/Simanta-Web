<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Vendor;
use App\Models\KategoriTagihan;
use App\Services\AktivitasService;
use Carbon\Carbon;

class TagihanController extends Controller
{
    // Halaman Kelola Data Tagihan
    public function index(Request $request)
    {
        $query = Tagihan::with(['vendor', 'kategori']);

        // Filter pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_invoice', 'like', '%'.$request->search.'%')
                  ->orWhere('nama_tagihan', 'like', '%'.$request->search.'%');
            });
        }

        $tagihans   = $query->orderBy('created_at', 'desc')->get();
        $kategoris  = KategoriTagihan::all();
        $vendors    = Vendor::orderBy('nama_vendor')->get();
        $nextInvoice = 'INV-' . date('Y') . '-' . str_pad(Tagihan::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('tagihan.index', compact('tagihans', 'kategoris', 'vendors', 'nextInvoice'));
    }

    // Simpan tagihan baru
    public function store(Request $request)
    {
        $request->validate([
            'nomor_invoice'        => 'required|unique:tagihans,nomor_invoice',
            'nama_tagihan'         => 'required',
            'vendor_id'            => 'required|exists:vendors,id',
            'kategori_id'          => 'required|exists:kategori_tagihans,id',
            'nominal'              => 'required|numeric|min:0',
            'tanggal_invoice'      => 'required|date',
            'tanggal_jatuh_tempo'  => 'required|date|after_or_equal:tanggal_invoice',
            'status'               => 'required|in:draft,upcoming,paid,archived',
        ], [
            'nomor_invoice.required'       => 'Nomor invoice wajib diisi.',
            'nomor_invoice.unique'         => 'Nomor invoice sudah digunakan.',
            'nama_tagihan.required'        => 'Nama tagihan wajib diisi.',
            'vendor_id.required'           => 'Vendor wajib dipilih.',
            'kategori_id.required'         => 'Kategori wajib dipilih.',
            'nominal.required'             => 'Nominal wajib diisi.',
            'nominal.numeric'              => 'Nominal harus berupa angka.',
            'tanggal_invoice.required'     => 'Tanggal invoice wajib diisi.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Jatuh tempo tidak boleh sebelum tanggal invoice.',
            'status.required'              => 'Status wajib dipilih.',
        ]);

        public function update(Request $request, Tagihan $tagihan)
        {
            $request->validate([
                'nomor_invoice'        => 'required|unique:tagihans,nomor_invoice,'.$tagihan->id,
                'nama_tagihan'         => 'required',
                'vendor_id'            => 'required|exists:vendors,id',
                'kategori_id'          => 'required|exists:kategori_tagihans,id',
                'nominal'              => 'required|numeric|min:0',
                'tanggal_invoice'      => 'required|date',
                'tanggal_jatuh_tempo'  => 'required|date|after_or_equal:tanggal_invoice',
                'status'               => 'required',
            ]);

            $tanggalReminder = Carbon::parse($request->tanggal_jatuh_tempo)
                ->subDays(7);

            $tagihan->update([
                'vendor_id'           => $request->vendor_id,
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
                'Mengubah tagihan '.$tagihan->nomor_invoice
            );

            return redirect()
                ->route('tagihan.index')
                ->with('success', 'Tagihan berhasil diperbarui.');
        }

        public function destroy(Tagihan $tagihan)
        {
            $invoice = $tagihan->nomor_invoice;

            AktivitasService::log(
                'Hapus Tagihan',
                'Tagihan',
                $tagihan->id,
                'Menghapus tagihan '.$invoice
            );

            $tagihan->delete();

            return redirect()
                ->route('tagihan.index')
                ->with('success', 'Tagihan berhasil dihapus.');
        }

        // Hitung tanggal reminder otomatis (7 hari sebelum jatuh tempo)
        $tanggalReminder = Carbon::parse($request->tanggal_jatuh_tempo)->subDays(7)->format('Y-m-d');

        $tagihan = Tagihan::create([
            'user_id'             => auth()->id(),
            'vendor_id'           => $request->vendor_id,
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
            'Menambahkan tagihan '.$tagihan->nomor_invoice.
            ' ('.$tagihan->nama_tagihan.') senilai Rp '.number_format($tagihan->nominal,0,',','.')
        );

        return redirect()->route('tagihan.index')
                         ->with('success', 'Tagihan '.$request->nomor_invoice.' berhasil ditambahkan!');
    }
}