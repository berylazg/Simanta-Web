<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CekDanKirimReminder extends Command
{
    protected $signature = 'app:cek-dan-kirim-reminder';
    protected $description = 'Cek tagihan jatuh tempo dan kirim reminder otomatis';

    public function handle()
    {
        $today = Carbon::today();

        // SEBELUM jatuh tempo: H-7, H-3, H-1
        foreach ([7, 3, 1] as $h) {
            $target = $today->copy()->addDays($h);
            $tagihans = Tagihan::where('status', 'upcoming')
                ->whereDate('tanggal_jatuh_tempo', $target)
                ->get();

            foreach ($tagihans as $t) {
                Mail::to($t->vendor->email ?? config('mail.from.address'))
                    ->send(new TagihanReminderMail($t, "H-$h"));

                Notifikasi::create([
                    'tagihan_id' => $t->id,
                    'tipe'       => 'upcoming',
                    'pesan'      => "Tagihan {$t->nama_tagihan} akan jatuh tempo dalam $h hari",
                ]);
            }
        }

        // SETELAH jatuh tempo: H+1, H+3, H+7
        foreach ([1, 3, 7] as $h) {
            $target = $today->copy()->subDays($h);
            $tagihans = Tagihan::where('status', 'upcoming')
                ->whereDate('tanggal_jatuh_tempo', $target)
                ->get();

            foreach ($tagihans as $t) {
                $t->update(['status' => 'overdue']);

                Mail::to($t->vendor->email ?? config('mail.from.address'))
                    ->send(new TagihanReminderMail($t, "H+$h Terlambat"));

                Notifikasi::create([
                    'tagihan_id' => $t->id,
                    'tipe'       => 'overdue',
                    'pesan'      => "Tagihan {$t->nama_tagihan} sudah terlambat $h hari",
                ]);
            }
        }

        $this->info('Pengecekan reminder selesai.');
    }
}
