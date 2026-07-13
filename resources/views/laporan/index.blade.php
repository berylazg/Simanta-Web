    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIMANTA — Laporan</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
    </head>
    <body class="simanta-app">

    @include('partials.sidebar', ['active' => 'laporan'])

    <div class="main">
        @include('partials.topbar', [
            'title' => 'Laporan',
            'subtitle' => 'Analisis pengeluaran dan ekspor data tagihan',
        ])

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
                    <select name="nama_vendor">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendor)
                            <option
                                value="{{ $vendor }}"
                                {{ request('nama_vendor') == $vendor ? 'selected' : '' }}>
                                {{ $vendor }}
                            </option>
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
                <div class="stat-card blue laporan-stat"><div class="stat-card-top"><div class="stat-icon blue">@include('partials.icon', ['name' => 'file-text'])</div></div><div class="val blue">{{ $totalTagihan }}</div><div class="lbl">Total Tagihan</div></div>
                <div class="stat-card green laporan-stat"><div class="stat-card-top"><div class="stat-icon green">@include('partials.icon', ['name' => 'mail'])</div></div><div class="val green">{{ $sudahDibayar }}</div><div class="lbl">Sudah Dibayar</div></div>
                <div class="stat-card yellow laporan-stat"><div class="stat-card-top"><div class="stat-icon amber">@include('partials.icon', ['name' => 'clock'])</div></div><div class="val yellow">{{ $belumDibayar }}</div><div class="lbl">Belum Dibayar</div></div>
                <div class="stat-card red laporan-stat"><div class="stat-card-top"><div class="stat-icon red">@include('partials.icon', ['name' => 'alert-circle'])</div></div><div class="val red">{{ $terlambat }}</div><div class="lbl">Terlambat</div></div>
                <div class="stat-card laporan-stat"><div class="stat-card-top"><div class="stat-icon purple">@include('partials.icon', ['name' => 'dollar-sign'])</div></div><div class="val purple">Rp {{ number_format($totalPengeluaran/1000000, 1) }} Jt</div><div class="lbl">Total Pengeluaran</div></div>
                <div class="stat-card laporan-stat"><div class="stat-card-top"><div class="stat-icon purple">@include('partials.icon', ['name' => 'trending-up'])</div></div><div class="val purple">Rp {{ number_format($tagihanTerbesar/1000000, 1) }} Jt</div><div class="lbl">Tagihan Terbesar</div></div>
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

    @include('partials.notif-script')

</body>
</html>
