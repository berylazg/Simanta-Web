<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Reminder;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    // Halaman testing reminder
    public function index()
    {
        // Tagihan yang perlu direminder: upcoming atau overdue
        $tagihanPerlu = Tagihan::with(['vendor', 'reminders'])
            ->whereIn('status', ['upcoming', 'overdue'])
            ->orderByRaw("CASE WHEN status='overdue' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        // Log reminder terakhir (riwayat)
        $riwayat = Reminder::with('tagihan')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('reminder.index', compact('tagihanPerlu', 'riwayat'));
    }

    // Kirim 1 reminder manual (tombol test)
    public function kirimSatu($id)
    {
        $tagihan = Tagihan::with('vendor')->findOrFail($id);

        try {
            Mail::to(config('mail.from.address'))->send(new TagihanReminderMail($tagihan));

            Reminder::create([
                'tagihan_id'   => $tagihan->id,
                'waktu_kirim'  => now(),
                'status_kirim' => 'terkirim',
                'email_tujuan' => config('mail.from.address'),
                'pesan'        => 'Reminder manual: ' . $tagihan->nomor_invoice . ' — ' . $tagihan->nama_tagihan,
            ]);

            return redirect()->route('reminder.index')
                ->with('success', '✅ Email reminder untuk ' . $tagihan->nomor_invoice . ' berhasil dikirim!');

        } catch (\Exception $e) {
            Reminder::create([
                'tagihan_id'   => $tagihan->id,
                'waktu_kirim'  => now(),
                'status_kirim' => 'gagal',
                'email_tujuan' => config('mail.from.address'),
                'pesan'        => 'Gagal kirim: ' . $e->getMessage(),
            ]);

            return redirect()->route('reminder.index')
                ->with('error', '❌ Gagal kirim email: ' . $e->getMessage());
        }
    }

    // Kirim SEMUA reminder sekaligus (simulasi job harian)
    public function kirimSemua()
    {
        $tagihanPerlu = Tagihan::with('vendor')
            ->whereIn('status', ['upcoming', 'overdue'])
            ->get();

        $berhasil = 0;
        $gagal = 0;

        foreach ($tagihanPerlu as $tagihan) {
            try {
                Mail::to(config('mail.from.address'))->send(new TagihanReminderMail($tagihan));

                Reminder::create([
                    'tagihan_id'   => $tagihan->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'terkirim',
                    'email_tujuan' => config('mail.from.address'),
                    'pesan'        => 'Reminder otomatis: ' . $tagihan->nomor_invoice,
                ]);
                $berhasil++;

            } catch (\Exception $e) {
                Reminder::create([
                    'tagihan_id'   => $tagihan->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'gagal',
                    'email_tujuan' => config('mail.from.address'),
                    'pesan'        => 'Gagal: ' . $e->getMessage(),
                ]);
                $gagal++;
            }
        }

        return redirect()->route('reminder.index')
            ->with('success', "✅ Selesai! {$berhasil} email terkirim, {$gagal} gagal.");
    }
}
