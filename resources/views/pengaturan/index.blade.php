<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Pengaturan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-app">

@include('partials.sidebar', ['active' => 'pengaturan'])

{{-- MAIN --}}
<div class="main">

    @include('partials.topbar', [
        'title' => 'Pengaturan',
        'subtitle' => 'Konfigurasi reminder email otomatis.',
    ])

    <div class="content">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('pengaturan.update') }}" method="POST">
    @csrf

        <div class="setting-grid">

            <!-- CARD PENGATURAN -->
            <div class="setting-card">

                <div class="setting-title">
                    Pengaturan Reminder
                </div>

            <div class="setting-item">

                <div>

                    <div class="setting-name">
                        Email Admin
                    </div>

                    <div class="setting-sub">
                        Email yang akan menerima reminder tagihan.
                    </div>

                </div>

            </div>

            <input
                type="email"
                name="admin_email"
                class="form-input"
                placeholder="Masukkan email admin"
                value="{{ old('admin_email', $setting->admin_email ?? '') }}"
                style="
                    width:100%;
                    padding:10px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                    margin-bottom:20px;
                ">
                
                <div class="mb-3">
                    <label class="form-label">Jam Pengiriman Reminder</label>

                    <input
                        type="time"
                        name="reminder_time"
                        class="form-control"
                        value="{{ old('reminder_time', $setting->reminder_time ? substr($setting->reminder_time,0,5) : '08:00') }}">
                </div>

                <div class="setting-item">

                    <div>
                        <strong>Aktifkan Reminder Email</strong>
                        <br>
                        <small style="color:#64748b;">
                            Sistem akan mengirim reminder otomatis.
                        </small>
                    </div>

                    <input
                        type="checkbox"
                        name="status"
                        {{ $setting && $setting->status ? 'checked' : '' }}>

                </div>

                <div class="setting-desc">
                    Atur kapan sistem akan mengirim email reminder kepada vendor sebelum jatuh tempo.
                </div>

                <div class="setting-item">
                    <span style="font-size:13px">30 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h30"
                        {{ $setting && $setting->h30 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span style="font-size:13px">14 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h14"
                        {{ $setting && $setting->h14 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span style="font-size:13px">7 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h7"
                        {{ $setting && $setting->h7 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span style="font-size:13px">3 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h3"
                        {{ $setting && $setting->h3 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span style="font-size:13px">1 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h1"
                        {{ $setting && $setting->h1 ? 'checked' : '' }}>
                </div>

                <div style="display:flex;gap:15px;margin-top:30px;">

                    <button type="submit" class="save-btn">
                        Simpan Pengaturan
                    </button>

                </form>

                <form action="{{ route('pengaturan.testEmail') }}" method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="save-btn"
                        style="background:#16a34a;">

                        Kirim Email Percobaan

                    </button>

                </form>

                </div>

                <div class="setting-card" style="margin-top:25px;">

    <div class="setting-title">
        Riwayat Pengiriman Reminder
    </div>

    <table style="width:100%;border-collapse:collapse;">

        <thead>

            <tr>

                <th>Tanggal</th>

                <th>Email</th>

                <th>Status</th>

            </tr>

        </thead>

        <tbody>

        @forelse($reminders as $reminder)

        <tr>

            <td>{{ $reminder->waktu_kirim }}</td>

            <td>{{ $reminder->email_tujuan }}</td>

            <td>

                @if($reminder->status_kirim=='terkirim')

                    <span style="color:green;">
                        Terkirim
                    </span>

                @elseif($reminder->status_kirim=='gagal')

                    <span style="color:red;">
                        Gagal
                    </span>

                @else

                    <span style="color:orange;">
                        Terjadwal
                    </span>

                @endif

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="3">
                Belum ada riwayat reminder.
            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>
            </div>

            <!-- CARD STATISTIK -->
            <div class="setting-card">

                <div class="setting-title">
                    Ringkasan Reminder
                </div>

                <div class="summary-item">
                    <small>Total Reminder Hari Ini</small>
                    <h2>18</h2>
                </div>

                <div class="summary-item">
                    <small>Berhasil</small>
                    <h2 style="color:#10b981;">15</h2>
                </div>

                <div class="summary-item">
                    <small>Gagal</small>
                    <h2 style="color:#ef4444;">3</h2>
                </div>

            </div>

        </div>
        </form>
    </div>{{-- end content --}}
</div>{{-- end main --}}

@include('partials.notif-script')

</body>
</html>
