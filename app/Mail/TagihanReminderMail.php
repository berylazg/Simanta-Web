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
    public string $reminder;

    public function __construct(Tagihan $tagihan, string $reminder)
    {
        $this->tagihan = $tagihan;
        $this->reminder = $reminder;
    }

    public function build()
    {
        $subject = $this->tagihan->status == 'overdue'
            ? "⚠️ Reminder {$this->reminder} - {$this->tagihan->nomor_invoice}"
            : "🔔 Reminder {$this->reminder} - {$this->tagihan->nomor_invoice}";

        return $this->subject($subject)
                    ->view('emails.reminder');
    }
}