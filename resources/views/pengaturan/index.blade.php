<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Pengaturan</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; background:#f1f5f9; display:flex; min-height:100vh; }

        /* SIDEBAR */
        .sidebar { width:220px; background:#1a2744; min-height:100vh; padding:20px 0; flex-shrink:0; }
        .sidebar-brand { padding:0 20px 20px; border-bottom:1px solid rgba(255,255,255,0.1); margin-bottom:20px; }
        .sidebar-brand h1 { color:white; font-size:18px; font-weight:800; }
        .sidebar-brand p { color:rgba(255,255,255,0.5); font-size:11px; }
        .sidebar-label { color:rgba(255,255,255,0.35); font-size:10px; font-weight:700; letter-spacing:1px; padding:0 20px; margin-bottom:6px; }
        .sidebar a { display:flex; align-items:center; gap:10px; padding:10px 20px; color:rgba(255,255,255,0.65); text-decoration:none; font-size:13px; transition:all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background:rgba(255,255,255,0.1); color:white; border-left:3px solid #005BAC; }
        .sidebar a.active { background:#005BAC; border-left:none; }
        .sidebar-footer { position:fixed; bottom:0; width:220px; padding:16px 20px; border-top:1px solid rgba(255,255,255,0.1); background:#1a2744; }
        .sidebar-footer p { color:white; font-size:13px; font-weight:600; }
        .sidebar-footer span { color:rgba(255,255,255,0.45); font-size:11px; }

        /* MAIN */
        .main { flex:1; display:flex; flex-direction:column; }
        .topbar { background:white; padding:14px 28px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
        .topbar h2 { color:#1e293b; font-size:18px; font-weight:700; }
        .topbar p { color:#64748b; font-size:12px; }
        .topbar-right { display:flex; align-items:center; gap:12px; }
        .topbar-date { color:#64748b; font-size:12px; }
        .user-badge { background:#005BAC; color:white; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:600; }

        .content { padding:24px 28px; }

        /* STATS */
        .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
        .stat-card { background:white; border-radius:12px; padding:20px; border:1px solid #e2e8f0; }
        .stat-card .val { font-size:32px; font-weight:800; color:#1e293b; }
        .stat-card .val.blue { color:#005BAC; }
        .stat-card .val.red { color:#ef4444; }
        .stat-card .val.yellow { color:#f59e0b; }
        .stat-card .val.green { color:#10b981; }
        .stat-card .val.purple { color:#8b5cf6; }
        .stat-card .lbl { color:#64748b; font-size:13px; margin-top:4px; }
        .stat-card .sub { color:#94a3b8; font-size:11px; margin-top:2px; }

        /* BADGE STATUS */
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-paid     { background:#dcfce7; color:#16a34a; }
        .badge-upcoming { background:#fef9c3; color:#ca8a04; }
        .badge-overdue  { background:#fee2e2; color:#dc2626; }
        .badge-draft    { background:#dbeafe; color:#2563eb; }

        /* TABLE */
        .card { background:white; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:24px; overflow:hidden; }
        .card-header { padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
        .card-header h3 { color:#1e293b; font-size:15px; font-weight:700; }
        .card-header p { color:#64748b; font-size:12px; }
        table { width:100%; border-collapse:collapse; }
        th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; padding:10px 16px; text-align:left; border-bottom:1px solid #e2e8f0; }
        td { padding:12px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#f8fafc; }
        .td-inv { color:#005BAC; font-weight:600; }
        .td-red { color:#ef4444; font-weight:600; }
        .td-sub { color:#94a3b8; font-size:11px; }

        /* AKTIVITAS */
        .aktivitas-list { padding:16px 20px; }
        .aktivitas-item { display:flex; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
        .aktivitas-item:last-child { border-bottom:none; }
        .akt-dot { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .akt-dot.green  { background:#dcfce7; }
        .akt-dot.blue   { background:#dbeafe; }
        .akt-dot.red    { background:#fee2e2; }
        .akt-dot.yellow { background:#fef9c3; }
        .akt-text p { color:#374151; font-size:13px; }
        .akt-text span { color:#94a3b8; font-size:11px; }

        /* 2 kolom bawah */
        .bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

        /* PENGATURAN */
        .setting-grid{
            display:grid;
            grid-template-columns:2fr 1fr;
            gap:20px;
        }

        .setting-card{
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:12px;
            padding:24px;
        }

        .setting-title{
            font-size:16px;
            font-weight:700;
            color:#1e293b;
            margin-bottom:6px;
        }

        .setting-desc{
            color:#64748b;
            font-size:13px;
            margin-bottom:25px;
        }

        .setting-item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:16px 0;
            border-bottom:1px solid #f1f5f9;
        }

        .setting-item:last-child{
            border-bottom:none;
        }

        .save-btn{
            margin-top:25px;
            background:#005BAC;
            color:white;
            border:none;
            padding:12px 24px;
            border-radius:8px;
            cursor:pointer;
            font-weight:600;
        }

        .summary-item{
            padding:18px 0;
            border-bottom:1px solid #f1f5f9;
        }

        .summary-item:last-child{
            border-bottom:none;
        }

        .summary-item h2{
            color:#005BAC;
            margin-top:5px;
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="sidebar-brand">
        <h1>SIMANTA</h1>
        <p>PT Surveyor Indonesia</p>
    </div>
    <a href="/dashboard">📊 Dashboard</a>
    <a href="/monitoring">📋 Monitoring Tagihan</a>
    <a href="/tagihan">🗂️ Kelola Data Tagihan</a>
    <a href="/pembayaran">💳 Mencatat Pembayaran</a>
    <a href="/laporan">📄 Laporan</a>
    <a href="/pengaturan"  class="active">⚙️ Pengaturan</a>
    <div class="sidebar-footer">
        <p>{{ auth()->user()->name }}</p>
        <span>administrator</span>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div>
            <h2>Pengaturan</h2>
            <p>Konfigurasi reminder email otomatis.</p>
        </div>
        <div class="topbar-right">
    <span class="topbar-date">📅 {{ now()->translatedFormat('l, d F Y') }}</span>

    {{-- IKON NOTIFIKASI --}}
    <div style="position:relative;">
        <button onclick="toggleNotif()" style="background:none;border:none;font-size:20px;cursor:pointer;position:relative;">
            🔔
            <span id="notifBadge" style="display:none;position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;font-weight:700;border-radius:50%;width:16px;height:16px;align-items:center;justify-content:center;">0</span>
        </button>
        <div id="notifDropdown" style="display:none;position:absolute;right:0;top:32px;width:320px;background:white;border:1px solid #e2e8f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.12);max-height:400px;overflow-y:auto;z-index:50;">
            <div id="notifList" style="padding:8px;"></div>
        </div>
    </div>

    <a href="{{ route('profile.edit') }}" class="user-badge" style="display:flex; align-items:center; gap:8px; text-decoration:none; cursor:pointer;">
        @if(auth()->user()->foto)
            <img src="{{ asset('storage/foto-profil/' . auth()->user()->foto) }}" style="width:24px; height:24px; border-radius:50%; object-fit:cover;">
        @endif
        {{ auth()->user()->name }}
    </a>
</div>
    </div>

    <div class="content">

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))

    <div style="
    background:#fee2e2;
    padding:15px;
    border-radius:8px;
    color:#991b1b;
    margin-bottom:20px;
    ">

    {{ session('error') }}

    </div>

    @endif

    @if ($errors->any())

    <div style="
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    ">

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
                    <span>30 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h30"
                        {{ $setting && $setting->h30 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span>14 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h14"
                        {{ $setting && $setting->h14 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span>7 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h7"
                        {{ $setting && $setting->h7 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span>3 Hari Sebelum Jatuh Tempo</span>
                    <input
                        type="checkbox"
                        name="h3"
                        {{ $setting && $setting->h3 ? 'checked' : '' }}>
                </div>

                <div class="setting-item">
                    <span>1 Hari Sebelum Jatuh Tempo</span>
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

<script>
async function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');
    const isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) await muatNotifikasi();
}

async function muatNotifikasi() {
    const res = await fetch("{{ route('notifikasi.data') }}");
    const data = await res.json();
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');

    list.innerHTML = '';
    if (data.notifikasi.length === 0) {
        list.innerHTML = '<p style="padding:20px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada notifikasi</p>';
    } else {
        data.notifikasi.forEach(n => {
            const warna = n.tipe === 'lunas' ? '#16a34a' : n.tipe === 'terlambat' ? '#dc2626' : '#ca8a04';
            list.innerHTML += `
                <div style="padding:10px 12px;border-bottom:1px solid #f1f5f9;cursor:pointer;background:${n.is_read ? 'white' : '#f0f9ff'};"
                     onclick="bacaNotif(${n.id})">
                    <p style="font-size:13px;color:#374151;border-left:3px solid ${warna};padding-left:8px;">${n.pesan}</p>
                    <span style="font-size:11px;color:#94a3b8;padding-left:11px;">${n.waktu}</span>
                </div>`;
        });
    }

    if (data.unread_count > 0) {
        badge.style.display = 'flex';
        badge.innerText = data.unread_count;
    } else {
        badge.style.display = 'none';
    }
}

async function bacaNotif(id) {
    await fetch(`/notifikasi/${id}/dibaca`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    muatNotifikasi();
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    if (!e.target.closest('[onclick="toggleNotif()"]') && !e.target.closest('#notifDropdown')) {
        dropdown.style.display = 'none';
    }
});

muatNotifikasi(); // load count saat halaman dibuka
</script>

</body>
</html>
