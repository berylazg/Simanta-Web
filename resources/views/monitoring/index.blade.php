<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Monitoring Tagihan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-app">

@include('partials.sidebar', ['active' => 'monitoring'])

{{-- MAIN --}}
<div class="main">

    @include('partials.topbar', [
        'title' => 'Monitoring Tagihan',
        'subtitle' => 'Pantau status dan detail seluruh tagihan perusahaan',
    ])

    <div class="content">

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="stat-card blue">
                <div class="stat-card-top"><div class="stat-icon blue">@include('partials.icon', ['name' => 'file-text'])</div></div>
                <div class="val">{{ $total }}</div>
                <div class="lbl">Total</div>
            </div>
            <div class="stat-card green">
                <div class="stat-card-top"><div class="stat-icon green">@include('partials.icon', ['name' => 'mail'])</div></div>
                <div class="val">{{ $sudahDibayar }}</div>
                <div class="lbl">Sudah Dibayar</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-card-top"><div class="stat-icon amber">@include('partials.icon', ['name' => 'clock'])</div></div>
                <div class="val">{{ $belumDibayar }}</div>
                <div class="lbl">Belum Dibayar</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-card-top"><div class="stat-icon orange">@include('partials.icon', ['name' => 'alert-triangle'])</div></div>
                <div class="val">{{ $akanJatuhTempo }}</div>
                <div class="lbl">Akan Jatuh Tempo</div>
            </div>
            <div class="stat-card red">
                <div class="stat-card-top"><div class="stat-icon red">@include('partials.icon', ['name' => 'alert-circle'])</div></div>
                <div class="val">{{ $terlambat }}</div>
                <div class="lbl">Terlambat</div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('monitoring.index') }}">
            <div class="filter-bar">
                <input type="text" name="search" placeholder="Cari no. invoice, nama tagihan..." value="{{ request('search') }}">

                <select name="nama_vendor">
                    <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor }}"
                                {{ request('nama_vendor') == $vendor ? 'selected' : '' }}>
                                {{ $vendor }}
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
                                <div class="td-sub">{{ $t->nama_vendor }}</div>
                            @endif
                        </td>
                        <td>{{ Str::limit($t->nama_vendor ?? '-', 20) }}</td>
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

@include('partials.notif-script')

</body>
</html>
