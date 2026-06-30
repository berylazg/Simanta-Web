<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Test Reminder</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; background:#f1f5f9; padding:24px; }
        .container { max-width:900px; margin:0 auto; }

        .header { background:#005BAC; color:white; padding:20px 24px; border-radius:12px 12px 0 0; }
        .header h1 { font-size:18px; }
        .header p { font-size:12px; opacity:0.8; margin-top:4px; }

        .card { background:white; border-radius:0 0 12px 12px; padding:20px 24px; margin-bottom:20px; border:1px solid #e2e8f0; border-top:none; }
        .card-standalone { background:white; border-radius:12px; padding:20px 24px; margin-bottom:20px; border:1px solid #e2e8f0; }

        .alert-success { background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; }
        .alert-error { background:#fee2e2; color:#dc2626; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; }

        .btn-kirim-semua { background:#16a34a; color:white; border:none; padding:12px 24px; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; margin-bottom:20px; }
        .btn-kirim-semua:hover { background:#15803d; }

        table { width:100%; border-collapse:collapse; }
        th { background:#f8fafc; padding:10px 14px; text-align:left; font-size:11px; color:#64748b; text-transform:uppercase; border-bottom:1px solid #e2e8f0; }
        td { padding:10px 14px; font-size:13px; border-bottom:1px solid #f1f5f9; }

        .badge { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-overdue { background:#fee2e2; color:#dc2626; }
        .badge-upcoming { background:#fef9c3; color:#ca8a04; }
        .badge-sent { background:#dcfce7; color:#16a34a; }
        .badge-failed { background:#fee2e2; color:#dc2626; }

        .btn-test { background:#005BAC; color:white; border:none; padding:6px 14px; border-radius:6px; font-size:12px; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-test:hover { background:#004a8f; }

        .back-link { color:#005BAC; text-decoration:none; font-size:13px; display:inline-block; margin-bottom:16px; }
    </style>
</head>
<body>
<div class="container">

    <a href="/dashboard" class="back-link">← Kembali ke Dashboard</a>

    <div class="header">
        <h1>🧪 Testing — Sistem Reminder Email</h1>
        <p>Halaman ini untuk menguji fitur kirim email reminder tagihan secara manual</p>
    </div>

    <div class="card">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('reminder.kirimSemua') }}">
            @csrf
            <button type="submit" class="btn-kirim-semua">
                📧 Kirim Reminder untuk SEMUA Tagihan Belum Bayar ({{ $tagihanPerlu->count() }})
            </button>
        </form>

        <h3 style="margin-bottom:12px;font-size:15px;color:#1e293b;">Tagihan yang Perlu Direminder</h3>

        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Nama Tagihan</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihanPerlu as $t)
                <tr>
                    <td><strong>{{ $t->nomor_invoice }}</strong></td>
                    <td>{{ Str::limit($t->nama_tagihan, 35) }}</td>
                    <td>{{ $t->tanggal_jatuh_tempo->format('d M Y') }}</td>
                    <td>
                        @if($t->status === 'overdue')
                            <span class="badge badge-overdue">Terlambat</span>
                        @else
                            <span class="badge badge-upcoming">Akan JT</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('reminder.kirimSatu', $t->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-test">📧 Kirim Test</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:24px;">Tidak ada tagihan yang perlu direminder</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-standalone">
        <h3 style="margin-bottom:12px;font-size:15px;color:#1e293b;">📜 Riwayat Pengiriman (15 terakhir)</h3>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Invoice</th>
                    <th>Email Tujuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $r)
                <tr>
                    <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $r->tagihan->nomor_invoice ?? '-' }}</td>
                    <td>{{ $r->email_tujuan }}</td>
                    <td>
                        @if($r->status_kirim === 'terkirim')
                            <span class="badge badge-sent">✔ Terkirim</span>
                        @elseif($r->status_kirim === 'gagal')
                            <span class="badge badge-failed">✕ Gagal</span>
                        @else
                            <span class="badge badge-upcoming">Dijadwalkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:24px;">Belum ada riwayat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
