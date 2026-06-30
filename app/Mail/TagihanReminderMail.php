<?php

namespace App\Mail;

use App\Models\Tagihan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TagihanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tagihan $tagihan;

    public function __construct(Tagihan $tagihan)
    {
        $this->tagihan = $tagihan;
    }

    public function build()
    {
        $subject = $this->tagihan->status === 'overdue'
            ? '⚠️ TERLAMBAT: Tagihan ' . $this->tagihan->nomor_invoice
            : '🔔 Reminder: Tagihan ' . $this->tagihan->nomor_invoice . ' Akan Jatuh Tempo';

        return $this->subject($subject)
                    ->view('emails.reminder-tagihan');
    }
}
