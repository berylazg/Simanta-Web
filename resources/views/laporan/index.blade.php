    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIMANTA — Laporan</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { font-family:'Segoe UI',sans-serif; background:#f1f5f9; display:flex; min-height:100vh; }

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

            .main { margin-left:220px; flex:1; width:calc(100% - 220px); }
            .topbar { background:white; padding:14px 28px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
            .topbar h2 { color:#1e293b; font-size:18px; font-weight:700; }
            .topbar p { color:#64748b; font-size:12px; }
            .user-badge { background:#005BAC; color:white; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:600; }
            .content { padding:24px 28px; }

            /* Filter */
            .filter-bar { background:white; border-radius:12px; border:1px solid #e2e8f0; padding:16px 20px; margin-bottom:24px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
            .filter-bar select { padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; color:#374151; background:#f8fafc; outline:none; cursor:pointer; min-width:140px; }
            .filter-bar select:focus { border-color:#005BAC; }
            .btn-filter { background:#005BAC; color:white; border:none; padding:9px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
            .btn-reset { background:#f1f5f9; color:#64748b; border:none; padding:9px 14px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block; }

            /* Stat cards */
            .stat-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:14px; margin-bottom:24px; }
            .stat-card { background:white; border-radius:12px; padding:16px; border:1px solid #e2e8f0; text-align:center; }
            .stat-card .val { font-size:22px; font-weight:800; color:#1e293b; }
            .stat-card .val.blue   { color:#005BAC; }
            .stat-card .val.green  { color:#10b981; }
            .stat-card .val.yellow { color:#f59e0b; }
            .stat-card .val.red    { color:#ef4444; }
            .stat-card .val.purple { color:#8b5cf6; }
            .stat-card .lbl { color:#64748b; font-size:11px; margin-top:4px; }

            /* Charts */
            .chart-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
            .chart-card { background:white; border-radius:12px; border:1px solid #e2e8f0; padding:20px; }
            .chart-card h3 { color:#1e293b; font-size:14px; font-weight:700; margin-bottom:4px; }
            .chart-card p { color:#64748b; font-size:11px; margin-bottom:16px; }
            .chart-wrap { position:relative; height:220px; }

            /* Kategori list */
            .kategori-list { display:flex; flex-direction:column; gap:10px; }
            .kategori-item { display:flex; align-items:center; gap:10px; }
            .kat-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
            .kat-name { flex:1; font-size:13px; color:#374151; }
            .kat-val { font-size:13px; font-weight:700; color:#1e293b; white-space:nowrap; }

            /* Status dist */
            .status-list { display:flex; flex-direction:column; gap:12px; }
            .status-item .status-row { display:flex; justify-content:space-between; margin-bottom:4px; }
            .status-item .status-label { font-size:13px; color:#374151; }
            .status-item .status-count { font-size:13px; font-weight:700; color:#1e293b; }
            .progress-bar { height:6px; background:#f1f5f9; border-radius:3px; overflow:hidden; }
            .progress-fill { height:100%; border-radius:3px; transition:width 0.4s; }
        </style>
    </head>
    <body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <h1>SIMANTA</h1>
            <p>PT Surveyor Indonesia</p>
        </div>
        <div class="sidebar-label">NAVIGASI</div>
        <a href="/dashboard">📊 Dashboard</a>
        <a href="/monitoring">📋 Monitoring Tagihan</a>
        <a href="/tagihan">🗂️ Kelola Data Tagihan</a>
        <a href="/pembayaran">💳 Mencatat Pembayaran</a>
        <a href="/laporan" class="active">📄 Laporan</a>
        <div class="sidebar-footer">
            <p>{{ auth()->user()->name }}</p>
            <span>administrator</span>
        </div>
    </div>


    <div class="main">
        <div class="topbar">
            <div>
                <h2>Laporan</h2>
                <p>Analisis pengeluaran dan ekspor data tagihan</p>
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

            {{-- FILTER --}}
            <form method="GET" action="{{ route('laporan.index') }}">
                <div class="filter-bar">
                    <select name="bulan">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="tahun">
                        <option value="">Semua Tahun</option>
                        @foreach(range(date('Y'), date('Y')-3) as $y)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <select name="vendor_id">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                    <select name="kategori_id">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-filter">Terapkan Filter</button>
                    <a href="{{ route('laporan.index') }}" class="btn-reset">Reset</a>
                </div>
            </form>

            {{-- STAT CARDS --}}
            <div class="stat-grid">
                <div class="stat-card"><div class="val blue">{{ $totalTagihan }}</div><div class="lbl">Total Tagihan</div></div>
                <div class="stat-card"><div class="val green">{{ $sudahDibayar }}</div><div class="lbl">Sudah Dibayar</div></div>
                <div class="stat-card"><div class="val yellow">{{ $belumDibayar }}</div><div class="lbl">Belum Dibayar</div></div>
                <div class="stat-card"><div class="val red">{{ $terlambat }}</div><div class="lbl">Terlambat</div></div>
                <div class="stat-card"><div class="val purple" style="font-size:14px;">Rp {{ number_format($totalPengeluaran/1000000, 1) }} Jt</div><div class="lbl">Total Pengeluaran</div></div>
                <div class="stat-card"><div class="val purple" style="font-size:14px;">Rp {{ number_format($tagihanTerbesar/1000000, 1) }} Jt</div><div class="lbl">Tagihan Terbesar</div></div>
            </div>

            {{-- CHART BARIS 1: Bar + Tren --}}
            <div class="chart-grid">
                <div class="chart-card">
                    <h3>Pengeluaran Bulanan</h3>
                    <p>Total tagihan per bulan (6 bulan terakhir)</p>
                    <div class="chart-wrap"><canvas id="chartBar"></canvas></div>
                </div>
                <div class="chart-card">
                    <h3>Tren Nilai Tagihan</h3>
                    <p>Perkembangan nilai tagihan 6 bulan terakhir</p>
                    <div class="chart-wrap"><canvas id="chartTren"></canvas></div>
                </div>
            </div>

            {{-- CHART BARIS 2: Kategori + Status --}}
            <div class="chart-grid">
                <div class="chart-card">
                    <h3>Pengeluaran per Kategori</h3>
                    <p>Distribusi nilai tagihan berdasarkan kategori</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:center;">
                        <div class="chart-wrap" style="height:180px;"><canvas id="chartKategori"></canvas></div>
                        <div class="kategori-list">
                            @php $colors = ['#005BAC','#10b981','#f59e0b','#ef4444','#8b5cf6','#f97316']; $ci=0; @endphp
                            @foreach($perKategori as $k)
                            <div class="kategori-item">
                                <div class="kat-dot" style="background:{{ $colors[$ci % count($colors)] }}"></div>
                                <span class="kat-name">{{ $k->nama_kategori }}</span>
                                <span class="kat-val">Rp {{ number_format($k->total/1000000,0) }} Jt</span>
                            </div>
                            @php $ci++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Distribusi Status Pembayaran</h3>
                    <p>Jumlah tagihan per status</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:center;">
                        <div class="chart-wrap" style="height:180px;"><canvas id="chartStatus"></canvas></div>
                        <div class="status-list">
                            @php $total = array_sum(array_column($statusDist,'val')) ?: 1; @endphp
                            @foreach($statusDist as $s)
                            <div class="status-item">
                                <div class="status-row">
                                    <span class="status-label" style="color:{{ $s['color'] }}">● {{ $s['label'] }}</span>
                                    <span class="status-count">{{ $s['val'] }}</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width:{{ ($s['val']/$total)*100 }}%;background:{{ $s['color'] }};"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    const labels = @json(array_column($bulanList,'label'));
    const paid   = @json(array_column($bulanList,'paid'));
    const unpaid = @json(array_column($bulanList,'unpaid'));

    // Bar chart
    new Chart(document.getElementById('chartBar'), {
        type:'bar',
        data:{ labels, datasets:[
            { label:'Lunas',        data:paid,   backgroundColor:'#005BAC' },
            { label:'Belum Bayar',  data:unpaid, backgroundColor:'#ef4444' }
        ]},
        options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'top'}},
            scales:{ y:{ ticks:{ callback: v => 'Rp '+(v/1000000).toFixed(0)+'Jt' } } } }
    });

    // Tren area
    new Chart(document.getElementById('chartTren'), {
        type:'line',
        data:{ labels, datasets:[{
            label:'Total Tagihan', data:paid.map((v,i)=>v+unpaid[i]),
            borderColor:'#005BAC', backgroundColor:'rgba(0,91,172,0.1)',
            fill:true, tension:0.4, pointBackgroundColor:'#005BAC'
        }]},
        options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}},
            scales:{ y:{ ticks:{ callback: v => 'Rp '+(v/1000000).toFixed(0)+'Jt' } } } }
    });

    // Donut kategori
    const katLabels = @json($perKategori->pluck('nama_kategori')->values());
    const katData   = @json($perKategori->pluck('total')->values());
    new Chart(document.getElementById('chartKategori'), {
        type:'doughnut',
        data:{ labels:katLabels, datasets:[{ data:katData,
            backgroundColor:['#005BAC','#10b981','#f59e0b','#ef4444','#8b5cf6','#f97316'],
            borderWidth:2 }]},
        options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}} }
    });

    // Donut status
    const stLabels = @json(array_column($statusDist,'label'));
    const stData   = @json(array_column($statusDist,'val'));
    const stColors = @json(array_column($statusDist,'color'));
    new Chart(document.getElementById('chartStatus'), {
        type:'doughnut',
        data:{ labels:stLabels, datasets:[{ data:stData, backgroundColor:stColors, borderWidth:2 }]},
        options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}} }
    });
    </script>

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

    </body>
    </html>
