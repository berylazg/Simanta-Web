<?php

namespace App\Console\Commands;

use App\Mail\TagihanReminderMail;
use App\Models\Notifikasi;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CekNotifikasiTagihan extends Command
{
    protected $signature = 'tagihan:cek-notifikasi';
    protected $description = 'Cek tagihan yang akan jatuh tempo / terlambat, buat notifikasi web, dan kirim email otomatis';

    public function handle()
    {
        $batasHari = 3; // H-3 sebelum jatuh tempo dianggap perlu notifikasi
        $hariIni = now()->startOfDay();

        $tagihan = Tagihan::whereIn('status', ['upcoming', 'overdue'])
            ->where('tanggal_jatuh_tempo', '<=', $hariIni->copy()->addDays($batasHari))
            ->get();

        $users = User::all();
        $dibuat = 0;

        foreach ($tagihan as $t) {
            $jenis = $t->status === 'overdue' ? 'terlambat' : 'jatuh_tempo';

            $judul = $jenis === 'terlambat'
                ? "Tagihan {$t->nomor_invoice} terlambat dibayar"
                : "Tagihan {$t->nomor_invoice} akan jatuh tempo";

            $pesan = $jenis === 'terlambat'
                ? "{$t->nama_tagihan} sudah melewati jatuh tempo (" . $t->tanggal_jatuh_tempo->translatedFormat('d M Y') . ")."
                : "{$t->nama_tagihan} akan jatuh tempo pada " . $t->tanggal_jatuh_tempo->translatedFormat('d M Y') . ".";

            foreach ($users as $user) {
                // Cegah duplikat: jangan buat notifikasi yang sama untuk tagihan+user di hari yang sama
                $sudahAda = Notifikasi::where('user_id', $user->id)
                    ->where('tagihan_id', $t->id)
                    ->where('jenis', $jenis)
                    ->whereDate('created_at', $hariIni)
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                Notifikasi::create([
                    'user_id' => $user->id,
                    'tagihan_id' => $t->id,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'jenis' => $jenis,
                ]);
                $dibuat++;

                // Kirim email otomatis ke user tersebut
                try {
                    Mail::to($user->email)->send(new TagihanReminderMail($t));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email notifikasi tagihan: ' . $e->getMessage());
                }
            }
        }

        $this->info("Selesai. {$dibuat} notifikasi baru dibuat untuk " . $tagihan->count() . " tagihan.");
        return Command::SUCCESS;
    }
}
