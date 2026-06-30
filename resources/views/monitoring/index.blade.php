<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Monitoring Tagihan</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; background:#f1f5f9; display:flex; min-height:100vh; }

        /* SIDEBAR */
        .sidebar { width:220px; background:#1a2744; min-height:100vh; padding:20px 0; flex-shrink:0; position:fixed; top:0; left:0; height:100%; }
        .sidebar-brand { padding:0 20px 20px; border-bottom:1px solid rgba(255,255,255,0.1); margin-bottom:20px; }
        .sidebar-brand h1 { color:white; font-size:18px; font-weight:800; }
        .sidebar-brand p { color:rgba(255,255,255,0.5); font-size:11px; }
        .sidebar-label { color:rgba(255,255,255,0.35); font-size:10px; font-weight:700; letter-spacing:1px; padding:0 20px; margin-bottom:6px; }
        .sidebar a { display:flex; align-items:center; gap:10px; padding:10px 20px; color:rgba(255,255,255,0.65); text-decoration:none; font-size:13px; transition:all 0.2s; }
        .sidebar a:hover { background:rgba(255,255,255,0.1); color:white; }
        .sidebar a.active { background:#005BAC; color:white; }
        .sidebar-footer { position:fixed; bottom:0; width:220px; padding:16px 20px; border-top:1px solid rgba(255,255,255,0.1); background:#1a2744; }
        .sidebar-footer p { color:white; font-size:13px; font-weight:600; }
        .sidebar-footer span { color:rgba(255,255,255,0.45); font-size:11px; }

        /* MAIN */
        .main { margin-left:220px; flex:1; width:calc(100% - 220px); }
        .topbar { background:white; padding:14px 28px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
        .topbar h2 { color:#1e293b; font-size:18px; font-weight:700; }
        .topbar p { color:#64748b; font-size:12px; }
        .user-badge { background:#005BAC; color:white; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:600; }

        .content { padding:24px 28px; }

        /* STAT CARDS */
        .stat-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px; }
        .stat-card { background:white; border-radius:12px; padding:18px 20px; border:1px solid #e2e8f0; border-top:3px solid transparent; }
        .stat-card.blue   { border-top-color:#005BAC; }
        .stat-card.green  { border-top-color:#10b981; }
        .stat-card.yellow { border-top-color:#f59e0b; }
        .stat-card.orange { border-top-color:#f97316; }
        .stat-card.red    { border-top-color:#ef4444; }
        .stat-card .val { font-size:30px; font-weight:800; }
        .stat-card.blue   .val { color:#005BAC; }
        .stat-card.green  .val { color:#10b981; }
        .stat-card.yellow .val { color:#f59e0b; }
        .stat-card.orange .val { color:#f97316; }
        .stat-card.red    .val { color:#ef4444; }
        .stat-card .lbl { color:#64748b; font-size:12px; margin-top:4px; }

        /* FILTER BAR */
        .filter-bar { background:white; border-radius:12px; border:1px solid #e2e8f0; padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .filter-bar input[type=text] { flex:1; min-width:220px; padding:9px 14px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none; color:#374151; background:#f8fafc; }
        .filter-bar input[type=text]:focus { border-color:#005BAC; background:white; }
        .filter-bar input[type=text]::placeholder { color:#94a3b8; }
        .filter-bar select { padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; color:#374151; background:#f8fafc; outline:none; cursor:pointer; min-width:150px; }
        .filter-bar select:focus { border-color:#005BAC; }
        .btn-filter { background:#005BAC; color:white; border:none; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
        .btn-filter:hover { background:#004a8f; }
        .btn-reset { background:#f1f5f9; color:#64748b; border:none; padding:9px 14px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-reset:hover { background:#e2e8f0; }

        /* INFO ROW */
        .info-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
        .info-row p { color:#64748b; font-size:13px; }
        .info-row strong { color:#1e293b; }

        /* TABLE */
        .card { background:white; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; padding:10px 16px; text-align:left; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
        td { padding:11px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#f8fafc; }

        .td-inv { color:#005BAC; font-weight:600; font-size:12px; }
        .td-sub { color:#94a3b8; font-size:11px; margin-top:2px; }
        .td-red { color:#ef4444; font-weight:600; }
        .td-orange { color:#f97316; font-weight:600; }

        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
        .badge-paid     { background:#dcfce7; color:#16a34a; }
        .badge-upcoming { background:#fef9c3; color:#ca8a04; }
        .badge-overdue  { background:#fee2e2; color:#dc2626; }
        .badge-draft    { background:#dbeafe; color:#2563eb; }
        .badge-archived { background:#f1f5f9; color:#64748b; }

        .reminder-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; }
        .reminder-badge.sent    { color:#16a34a; }
        .reminder-badge.sched   { color:#2563eb; }
        .reminder-badge.none    { color:#94a3b8; }

        .empty-state { text-align:center; padding:48px; color:#94a3b8; }
        .empty-state p { font-size:14px; margin-top:8px; }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="sidebar-brand">
        <h1>SIMANTA</h1>
        <p>PT Surveyor Indonesia</p>
    </div>
    <div class="sidebar-label">NAVIGASI</div>
    <a href="/dashboard">📊 Dashboard</a>
    <a href="/monitoring" class="active">📋 Monitoring Tagihan</a>
    <a href="/tagihan">🗂️ Kelola Data Tagihan</a>
    <a href="/pembayaran">💳 Mencatat Pembayaran</a>
    <a href="/laporan">📄 Laporan</a>
    <div class="sidebar-footer">
        <p>{{ auth()->user()->name }}</p>
        <span>administrator</span>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    <div class="topbar">
        <div>
            <h2>Monitoring Tagihan</h2>
            <p>Pantau status dan detail seluruh tagihan perusahaan</p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <span style="color:#64748b;font-size:12px;">📅 {{ now()->translatedFormat('l, d F Y') }}</span>

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

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="stat-card blue">
                <div class="val">{{ $total }}</div>
                <div class="lbl">Total</div>
            </div>
            <div class="stat-card green">
                <div class="val">{{ $sudahDibayar }}</div>
                <div class="lbl">Sudah Dibayar</div>
            </div>
            <div class="stat-card yellow">
                <div class="val">{{ $belumDibayar }}</div>
                <div class="lbl">Belum Dibayar</div>
            </div>
            <div class="stat-card orange">
                <div class="val">{{ $akanJatuhTempo }}</div>
                <div class="lbl">Akan Jatuh Tempo</div>
            </div>
            <div class="stat-card red">
                <div class="val">{{ $terlambat }}</div>
                <div class="lbl">Terlambat</div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('monitoring.index') }}">
            <div class="filter-bar">
                <input type="text" name="search" placeholder="🔍  Cari no. invoice, nama tagihan..." value="{{ request('search') }}">

                <select name="vendor_id">
                    <option value="">Semua Vendor</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->nama_vendor }}
                        </option>
                    @endforeach
                </select>

                <select name="kategori_id">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Jatuh Tempo</option>
                    <option value="paid"     {{ request('status') == 'paid'     ? 'selected' : '' }}>Lunas</option>
                    <option value="overdue"  {{ request('status') == 'overdue'  ? 'selected' : '' }}>Terlambat</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>

                <button type="submit" class="btn-filter">Cari</button>
                <a href="{{ route('monitoring.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>

        {{-- INFO + TABEL --}}
        <div class="info-row">
            <p>Menampilkan <strong>{{ $tagihans->count() }}</strong> tagihan</p>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Nama Tagihan</th>
                        <th>Vendor</th>
                        <th>Kategori</th>
                        <th>Tgl Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Reminder</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihans as $t)
                    <tr>
                        <td class="td-inv">{{ $t->nomor_invoice }}</td>
                        <td>
                            {{ Str::limit($t->nama_tagihan, 38) }}
                            @if($t->vendor)
                                <div class="td-sub">{{ $t->vendor->nama_vendor }}</div>
                            @endif
                        </td>
                        <td>{{ Str::limit($t->vendor->nama_vendor ?? '-', 20) }}</td>
                        <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $t->tanggal_invoice ? $t->tanggal_invoice->format('d M Y') : '-' }}</td>
                        <td>
                            @if($t->tanggal_jatuh_tempo)
                                @if($t->status === 'overdue')
                                    <span class="td-red">{{ $t->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                    <div class="td-sub td-red">Terlambat {{ (int) now()->diffInDays($t->tanggal_jatuh_tempo) }} hari</div>
                                @elseif($t->status === 'upcoming')
                                    <span class="td-orange">{{ $t->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                    <div class="td-sub td-orange">{{ (int) $t->tanggal_jatuh_tempo->diffInDays(now()) }} hari lagi</div>
                                @else
                                    {{ $t->tanggal_jatuh_tempo->format('d M Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td style="white-space:nowrap;">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status === 'paid')
                                <span class="badge badge-paid">Lunas</span>
                            @elseif($t->status === 'upcoming')
                                <span class="badge badge-upcoming">Akan JT</span>
                            @elseif($t->status === 'overdue')
                                <span class="badge badge-overdue">Terlambat</span>
                            @elseif($t->status === 'archived')
                                <span class="badge badge-archived">Diarsipkan</span>
                            @else
                                <span class="badge badge-draft">Draft</span>
                            @endif
                        </td>
                        <td>
                            @php $lastReminder = $t->reminders->sortByDesc('waktu_kirim')->first(); @endphp
                                @if($lastReminder && $lastReminder->status_kirim === 'terkirim')
                                <span class="reminder-badge sent">✔ Terkirim</span>
                                @elseif($lastReminder && $lastReminder->status_kirim === 'dijadwalkan')
                                <span class="reminder-badge sched">🕐 Dijadwalkan</span>
                                @else
                                <span class="reminder-badge none">— Belum</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                🔍
                                <p>Tidak ada tagihan yang sesuai filter</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

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
            const warna = n.tipe === 'paid' ? '#16a34a' : n.tipe === 'overdue' ? '#dc2626' : '#ca8a04';
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

muatNotifikasi();
</script>

</body>
</html>
