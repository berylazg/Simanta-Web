<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReminderSetting;
use App\Models\Reminder;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestReminderMail;

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

        $setting->h30 = $request->has('h30');
        $setting->h14 = $request->has('h14');
        $setting->h7 = $request->has('h7');
        $setting->h3 = $request->has('h3');
        $setting->h1 = $request->has('h1');

        $setting->save();

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

            return back()->with('success', 'Email percobaan berhasil dikirim.');

        } catch (\Exception $e) {

            return back()->with('error', 'Gagal mengirim email: '.$e->getMessage());

        }
    }
}