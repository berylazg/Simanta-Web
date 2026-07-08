<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestReminderMail extends Mailable
{
    public function build()
    {
        return $this->subject('Email Percobaan SIMANTA')
                    ->view('emails.test-reminder');
    }
}