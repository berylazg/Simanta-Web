<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\ReminderSetting;
use App\Models\Reminder;

class CekDanKirimReminder extends Command
{
    protected $signature = 'app:cek-dan-kirim-reminder';
    protected $description = 'Cek tagihan jatuh tempo dan kirim reminder otomatis';

    public function handle()
    {
        $today = Carbon::today();

        $setting = ReminderSetting::first();

        if (!$setting || !$setting->status) {

            $this->info('Reminder dinonaktifkan.');

            return;
        }
// Menentukan reminder yang aktif dari Pengaturan
$reminderHari = [];

if ($setting->h30) $reminderHari[] = 30;
if ($setting->h14) $reminderHari[] = 14;
if ($setting->h7)  $reminderHari[] = 7;
if ($setting->h3)  $reminderHari[] = 3;
if ($setting->h1)  $reminderHari[] = 1;

        // Kirim reminder sesuai pengaturan
        foreach ($reminderHari as $h) {

            $target = $today->copy()->addDays($h);

            $tagihans = Tagihan::where('status', 'upcoming')
                ->whereDate('tanggal_jatuh_tempo', $target)
                ->get();

            foreach ($tagihans as $t) {

                Mail::to($setting->admin_email)
                    ->send(new TagihanReminderMail($t, "H-$h"));

                Reminder::create([
                    'tagihan_id'   => $t->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'terkirim',
                    'email_tujuan' => $setting->admin_email,
                    'pesan'        => "Reminder H-$h berhasil dikirim",
                ]);

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

                Mail::to($setting->admin_email)
                    ->send(new TagihanReminderMail($t, "H+$h Terlambat"));

                Reminder::create([
                    'tagihan_id'   => $t->id,
                    'waktu_kirim'  => now(),
                    'status_kirim' => 'terkirim',
                    'email_tujuan' => $setting->admin_email,
                    'pesan'        => "Reminder H-$h berhasil dikirim",
                ]);

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
