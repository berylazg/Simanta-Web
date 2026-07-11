<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReminderSetting;
use App\Models\Reminder;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestReminderMail;
use App\Services\AktivitasService;

class PengaturanController extends Controller
{
    public function index()
    {
    $setting = ReminderSetting::first();

    $reminders = Reminder::with('tagihan')
                    ->latest()
                    ->take(10)
                    ->get();

    return view(
        'pengaturan.index',
        compact(
            'setting',
            'reminders'
            )
        );
    }

    public function update(Request $request)
    {
        $request->validate([
        'admin_email' => 'required|email'
        ]);
        $setting = ReminderSetting::first();

        if (!$setting) {
            $setting = new ReminderSetting();
        }

        $setting->status = $request->has('status');

        $setting->admin_email = $request->admin_email;
        $setting->reminder_time = $request->reminder_time;

        $setting->h30 = $request->has('h30');
        $setting->h14 = $request->has('h14');
        $setting->h7 = $request->has('h7');
        $setting->h3 = $request->has('h3');
        $setting->h1 = $request->has('h1');

        $setting->save();

        AktivitasService::log(
            'Update Pengaturan Reminder',
            'ReminderSetting',
            $setting->id,
            'Pengaturan reminder diperbarui. Email: '.$setting->admin_email.
            ', Jam: '.$setting->reminder_time
        );

        return redirect()->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function testEmail()
    {
        $setting = ReminderSetting::first();

        if (!$setting || !$setting->admin_email) {
            return back()->with('error', 'Email admin belum diatur.');
        }

        try {

            Mail::to($setting->admin_email)
                ->send(new TestReminderMail());
            
            AktivitasService::log(
                'Test Email',
                'Reminder',
                null,
                'Email percobaan berhasil dikirim ke '.$setting->admin_email
            );

            return back()->with('success', 'Email percobaan berhasil dikirim.');

        } catch (\Exception $e) {

            AktivitasService::log(
                'Test Email Gagal',
                'Reminder',
                null,
                'Gagal mengirim email percobaan ke '.$setting->admin_email.
                '. Error: '.$e->getMessage()
            );
            
            return back()->with('error', 'Gagal mengirim email: '.$e->getMessage());

        }
    }
}