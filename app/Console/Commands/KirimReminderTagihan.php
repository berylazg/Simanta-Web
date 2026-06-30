<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\Reminder;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class KirimReminderTagihan extends Command
{
    protected $signature = 'reminder:kirim';
    protected $description = 'Kirim email reminder untuk tagihan yang akan jatuh tempo atau terlambat';

    public function handle()
    {
        $this->info('Mengecek tagihan yang perlu direminder...');

        // Tagihan upcoming yang reminder date-nya hari ini atau sudah lewat
        // dan belum pernah dikirim reminder hari ini
        $tagihanPerluReminder = Tagihan::whereIn('status', ['upcoming', 'overdue'])
            ->where(function($q) {
                $q->whereDate('tanggal_reminder', '<=', Carbon::today())
                  ->orWhere('status', 'overdue');
            })
            ->get();

        $count = 0;

        foreach ($tagihanPerluReminder as $tagihan) {
            // Skip kalau sudah dikirim reminder hari ini
            $sudahDikirimHariIni = Reminder::where('tagihan_id', $tagihan->id)
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if ($sudahDikirimHariIni) continue;

            try {
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new TagihanReminderMail($tagihan));

                Reminder::create([
                    'tagihan_id'   => $tagihan->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'terkirim',
                    'email_tujuan' => env('MAIL_FROM_ADDRESS'),
                    'pesan'        => 'Reminder otomatis harian: ' . $tagihan->nomor_invoice,
                ]);

                $count++;
                $this->info("✅ Terkirim: {$tagihan->nomor_invoice}");

            } catch (\Exception $e) {
                Reminder::create([
                    'tagihan_id'   => $tagihan->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'gagal',
                    'email_tujuan' => env('MAIL_FROM_ADDRESS'),
                    'pesan'        => 'Gagal: ' . $e->getMessage(),
                ]);
                $this->error("❌ Gagal: {$tagihan->nomor_invoice}");
            }
        }

        $this->info("Selesai! Total {$count} email reminder terkirim.");
    }
}
