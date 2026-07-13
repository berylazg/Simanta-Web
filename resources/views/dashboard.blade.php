<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-app">

@include('partials.sidebar', ['active' => 'dashboard'])

{{-- MAIN --}}
<div class="main">

    @include('partials.topbar', [
        'title' => 'Dashboard',
        'subtitle' => 'Ringkasan monitoring tagihan operasional PT Surveyor Indonesia Cabang Palembang',
    ])

    <div class="content">

        {{-- WELCOME BANNER --}}
        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
                <p>Berikut ringkasan status tagihan operasional PT Surveyor Indonesia Cabang Palembang.</p>
                <div class="welcome-actions">
                    <a href="{{ route('tagihan.index') }}" class="welcome-btn-primary">
                        @include('partials.icon', ['name' => 'plus'])
                        Tambah Tagihan
                    </a>
                    <a href="{{ route('monitoring.index') }}" class="welcome-btn-secondary">
                        Lihat Monitoring
                        @include('partials.icon', ['name' => 'chevron-right'])
                    </a>
                </div>
            </div>
            <div class="welcome-pills">
                <div class="welcome-pill">
                    <div class="val amber">{{ $belumDibayar }}</div>
                    <div class="lbl">Tagihan Aktif</div>
                </div>
                <div class="welcome-pill">
                    <div class="val rose">{{ $terlambat }}</div>
                    <div class="lbl">Terlambat</div>
                </div>
                <div class="welcome-pill">
                    <div class="val mint">{{ $reminderTerkirim }}</div>
                    <div class="lbl">Email Terkirim</div>
                </div>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon blue">@include('partials.icon', ['name' => 'file-text'])</div>
                </div>
                <div class="val blue">{{ $totalTagihan }}</div>
                <div class="lbl">Total Tagihan</div>
                <div class="sub">Semua periode</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon red">@include('partials.icon', ['name' => 'alert-triangle'])</div>
                </div>
                <div class="val red">{{ $jatuhTempoMingguIni }}</div>
                <div class="lbl">Jatuh Tempo Minggu Ini</div>
                <div class="sub">Perlu segera dibayar</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon purple">@include('partials.icon', ['name' => 'dollar-sign'])</div>
                </div>
                <div class="val purple">Rp {{ number_format($totalNilaiTagihan, 0, ',', '.') }}</div>
                <div class="lbl">Total Nilai Tagihan Aktif</div>
                <div class="sub">Belum terbayar</div>
            </div>
        </div>

        {{-- TABEL TAGIHAN PENTING --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3>Tagihan Mendatang & Terlambat</h3>
                    <p>Tagihan yang memerlukan perhatian segera</p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Nama Tagihan</th>
                        <th>Vendor</th>
                        <th>Kategori</th>
                        <th>Jatuh Tempo</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihanPenting as $t)
                    <tr>
                        <td class="td-inv">{{ $t->nomor_invoice }}</td>
                        <td>
                            {{ Str::limit($t->nama_tagihan, 35) }}
                            <div class="td-sub">{{ $t->kategori->nama_kategori ?? '-' }}</div>
                        </td>
                        <td>{{ $t->vendor->nama_vendor ?? '-' }}</td>
                        <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            <span class="{{ $t->status === 'overdue' ? 'td-red' : '' }}">
                                {{ $t->tanggal_jatuh_tempo->format('d M Y') }}
                            </span>
                            <div class="td-sub">
                                @if($t->status === 'overdue')
                                    Terlambat {{ (int) now()->diffInDays($t->tanggal_jatuh_tempo) }} hari
                                @else
                                    {{ (int) $t->tanggal_jatuh_tempo->diffInDays(now()) }} hari lagi
                                @endif
                            </div>
                        </td>
                        <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status === 'paid')
                                <span class="badge badge-paid">Lunas</span>
                            @elseif($t->status === 'upcoming')
                                <span class="badge badge-upcoming">Akan JT</span>
                            @elseif($t->status === 'overdue')
                                <span class="badge badge-overdue">Terlambat</span>
                            @else
                                <span class="badge badge-draft">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#94a3b8;padding:32px;">
                            Tidak ada tagihan yang memerlukan perhatian
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BAWAH: Distribusi Status + Aktivitas --}}
        <div class="bottom-grid">

            {{-- Distribusi Status --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3>Distribusi Status Tagihan</h3>
                        <p>Jumlah tagihan per status</p>
                    </div>
                </div>
                <div style="padding:20px;">
                    <table>
                        <tr>
                            <td><span class="badge badge-paid">Lunas</span></td>
                            <td style="text-align:right;font-weight:700;color:#16a34a;">{{ $statusDistribusi['paid'] }} tagihan</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-upcoming">Akan Jatuh Tempo</span></td>
                            <td style="text-align:right;font-weight:700;color:#ca8a04;">{{ $statusDistribusi['upcoming'] }} tagihan</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-overdue">Terlambat</span></td>
                            <td style="text-align:right;font-weight:700;color:#dc2626;">{{ $statusDistribusi['overdue'] }} tagihan</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-draft">Draft</span></td>
                            <td style="text-align:right;font-weight:700;color:#2563eb;">{{ $statusDistribusi['draft'] }} tagihan</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Aktivitas Terbaru --}}
            <div class="card">
                <div class="card-header">
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <div class="aktivitas-list">
                    @foreach($aktivitasTerbaru as $log)
                    <div class="aktivitas-item">
                        <div class="akt-dot {{ str_contains($log->aksi, 'Pembayaran') ? 'green' : (str_contains($log->aksi, 'Reminder') ? 'blue' : (str_contains($log->aksi, 'Overdue') ? 'red' : 'yellow')) }}">
                            {{ str_contains($log->aksi, 'Pembayaran') ? '✅' : (str_contains($log->aksi, 'Reminder') ? '📧' : (str_contains($log->aksi, 'Overdue') ? '⚠️' : '➕')) }}
                        </div>
                        <div class="akt-text">
                            <p>{{ $log->keterangan }}</p>
                            <span>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>{{-- end content --}}
</div>{{-- end main --}}

@include('partials.notif-script')

</body>
</html>
