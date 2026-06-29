<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Kelola Data Tagihan</title>
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
        .main { margin-left:220px; flex:1; display:flex; flex-direction:column; width:calc(100% - 220px); }
        .topbar { background:white; padding:14px 28px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
        .topbar h2 { color:#1e293b; font-size:18px; font-weight:700; }
        .topbar p { color:#64748b; font-size:12px; }
        .topbar-right { display:flex; align-items:center; gap:12px; }
        .topbar-date { color:#64748b; font-size:12px; }
        .user-badge { background:#005BAC; color:white; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:600; }

        .content { padding:24px 28px; }

        /* TOOLBAR */
        .toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:12px; }
        .search-box { display:flex; align-items:center; gap:8px; background:white; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 14px; flex:1; max-width:400px; }
        .search-box input { border:none; outline:none; font-size:13px; color:#374151; width:100%; background:transparent; }
        .search-box input::placeholder { color:#94a3b8; }

        .btn-primary { background:#005BAC; color:white; border:none; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; transition:background 0.2s; text-decoration:none; }
        .btn-primary:hover { background:#004a8f; }

        /* ALERT */
        .alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#16a34a; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13px; }
        .alert-error   { background:#fee2e2; border:1px solid #fecaca; color:#dc2626; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13px; }

        /* TABLE */
        .card { background:white; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
        .card-header { padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
        .card-header h3 { color:#1e293b; font-size:15px; font-weight:700; }
        .card-header p { color:#64748b; font-size:12px; }
        table { width:100%; border-collapse:collapse; }
        th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; padding:10px 16px; text-align:left; border-bottom:1px solid #e2e8f0; }
        td { padding:12px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#f8fafc; }
        .td-inv { color:#005BAC; font-weight:600; }
        .td-sub { color:#94a3b8; font-size:11px; margin-top:2px; }

        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-paid     { background:#dcfce7; color:#16a34a; }
        .badge-upcoming { background:#fef9c3; color:#ca8a04; }
        .badge-overdue  { background:#fee2e2; color:#dc2626; }
        .badge-draft    { background:#dbeafe; color:#2563eb; }
        .badge-archived { background:#f1f5f9; color:#64748b; }

        .kategori-badge { background:#eff6ff; color:#3b82f6; padding:3px 8px; border-radius:6px; font-size:11px; }

        /* MODAL OVERLAY */
        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
        .modal-overlay.active { display:flex; }

        .modal { background:white; border-radius:16px; width:100%; max-width:660px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.3); }
        .modal-header { padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:flex-start; position:sticky; top:0; background:white; z-index:1; }
        .modal-header h3 { color:#1e293b; font-size:17px; font-weight:700; }
        .modal-header p { color:#64748b; font-size:12px; margin-top:2px; }
        .modal-close { background:none; border:none; color:#94a3b8; font-size:20px; cursor:pointer; padding:4px; line-height:1; }
        .modal-close:hover { color:#374151; }
        .modal-body { padding:24px; }
        .modal-footer { padding:16px 24px; border-top:1px solid #f1f5f9; display:flex; justify-content:flex-end; gap:10px; position:sticky; bottom:0; background:white; }

        /* FORM */
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .form-row.full { grid-template-columns:1fr; }
        .form-row.three { grid-template-columns:1fr 1fr 1fr; }

        .form-group { display:flex; flex-direction:column; gap:6px; margin-bottom:0; }
        .form-group label { color:#374151; font-size:11px; font-weight:700; letter-spacing:0.5px; text-transform:uppercase; }
        .form-group label .required { color:#ef4444; margin-left:2px; }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding:10px 12px;
            border:1.5px solid #e2e8f0;
            border-radius:8px;
            font-size:13px;
            color:#1e293b;
            outline:none;
            background:#f8fafc;
            transition:border-color 0.2s;
            font-family:'Segoe UI',sans-serif;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color:#005BAC;
            background:white;
            box-shadow:0 0 0 3px rgba(0,91,172,0.08);
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder { color:#94a3b8; }
        .form-group .hint { color:#94a3b8; font-size:11px; }

        .input-prefix { display:flex; align-items:center; }
        .input-prefix span { background:#e2e8f0; padding:10px 12px; border:1.5px solid #e2e8f0; border-right:none; border-radius:8px 0 0 8px; font-size:13px; color:#64748b; font-weight:600; }
        .input-prefix input { border-radius:0 8px 8px 0; flex:1; }

        .btn-cancel { background:#f1f5f9; color:#64748b; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
        .btn-cancel:hover { background:#e2e8f0; }
        .btn-save { background:#005BAC; color:white; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; }
        .btn-save:hover { background:#004a8f; }

        .field-error { color:#dc2626; font-size:11px; margin-top:2px; }
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
    <a href="/monitoring">📋 Monitoring Tagihan</a>
    <a href="/tagihan" class="active">🗂️ Kelola Data Tagihan</a>
    <a href="#">💳 Mencatat Pembayaran</a>
    <a href="#">📄 Laporan</a>
    <div class="sidebar-footer">
        <p>{{ auth()->user()->name }}</p>
        <span>administrator</span>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    <div class="topbar">
        <div>
            <h2>Kelola Data Tagihan</h2>
            <p>Tambah, ubah, dan hapus data tagihan operasional</p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">📅 {{ now()->translatedFormat('l, d F Y') }}</span>
            <span class="user-badge">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="content">

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Alert error validasi --}}
        @if($errors->any())
            <div class="alert-error">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        {{-- TOOLBAR --}}
        <div class="toolbar">
            <form method="GET" action="{{ route('tagihan.index') }}" style="flex:1;max-width:400px;">
                <div class="search-box">
                    <span>🔍</span>
                    <input type="text" name="search" placeholder="Cari no. invoice atau nama tagihan..." value="{{ request('search') }}">
                </div>
            </form>
            <button class="btn-primary" onclick="bukaModal()">
                ➕ Tambah Tagihan
            </button>
        </div>

        {{-- TABEL --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3>{{ $tagihans->count() }} Tagihan Terdaftar</h3>
                    <p>Semua data tagihan operasional</p>
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
                    @forelse($tagihans as $t)
                    <tr>
                        <td class="td-inv">{{ $t->nomor_invoice }}</td>
                        <td>
                            {{ Str::limit($t->nama_tagihan, 40) }}
                            @if($t->nomor_kontrak)
                                <div class="td-sub">{{ $t->nomor_kontrak }}</div>
                            @endif
                        </td>
                        <td>{{ $t->vendor->nama_vendor ?? '-' }}</td>
                        <td><span class="kategori-badge">{{ $t->kategori->nama_kategori ?? '-' }}</span></td>
                        <td>{{ $t->tanggal_jatuh_tempo ? $t->tanggal_jatuh_tempo->format('d M Y') : '-' }}</td>
                        <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#94a3b8;padding:40px;">
                            Belum ada data tagihan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ===== MODAL TAMBAH TAGIHAN ===== --}}
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3>Tambah Tagihan Baru</h3>
                <p>Isi formulir data tagihan operasional perusahaan</p>
            </div>
            <button class="modal-close" onclick="tutupModal()">✕</button>
        </div>

        <form method="POST" action="{{ route('tagihan.store') }}">
            @csrf
            <div class="modal-body">

                {{-- Baris 1: Nomor Invoice + Nomor Kontrak --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nomor Invoice <span class="required">*</span></label>
                        <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice', $nextInvoice) }}" placeholder="INV-2026-019" required>
                        @error('nomor_invoice')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Kontrak</label>
                        <input type="text" name="nomor_kontrak" value="{{ old('nomor_kontrak') }}" placeholder="KTR/2026/XXX/001">
                    </div>
                </div>

                {{-- Baris 2: Nama Tagihan --}}
                <div class="form-row full">
                    <div class="form-group">
                        <label>Nama Tagihan <span class="required">*</span></label>
                        <input type="text" name="nama_tagihan" value="{{ old('nama_tagihan') }}" placeholder="contoh: Sewa Kendaraan Operasional — Januari 2026" required>
                        @error('nama_tagihan')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 3: Vendor + Kategori --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Vendor <span class="required">*</span></label>
                        <select name="vendor_id" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>
                                    {{ $v->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <select name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 4: Nominal --}}
                <div class="form-row full">
                    <div class="form-group">
                        <label>Nominal (Rp) <span class="required">*</span></label>
                        <div class="input-prefix">
                            <span>Rp</span>
                            <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="Contoh: 5000000" min="0" required>
                        </div>
                        @error('nominal')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 5: Tanggal Invoice + Jatuh Tempo + Reminder --}}
                <div class="form-row three">
                    <div class="form-group">
                        <label>Tanggal Invoice</label>
                        <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice') }}">
                        @error('tanggal_invoice')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Jatuh Tempo <span class="required">*</span></label>
                        <input type="date" name="tanggal_jatuh_tempo" id="jatuhTempo" value="{{ old('tanggal_jatuh_tempo') }}" required>
                        @error('tanggal_jatuh_tempo')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Reminder Date</label>
                        <input type="text" id="reminderDate" placeholder="Otomatis" readonly style="background:#f1f5f9;color:#64748b;">
                        <div class="hint">7 hari sebelum jatuh tempo</div>
                    </div>
                </div>

                {{-- Baris 6: Status --}}
                <div class="form-row full">
                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="draft"    {{ old('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Akan Jatuh Tempo</option>
                            <option value="paid"     {{ old('status') == 'paid'     ? 'selected' : '' }}>Lunas</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        @error('status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>{{-- end modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-save">✔ Simpan Tagihan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Buka modal
    function bukaModal() {
        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Tutup modal
    function tutupModal() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Klik di luar modal = tutup
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) tutupModal();
    });

    // Hitung reminder date otomatis
    document.getElementById('jatuhTempo').addEventListener('change', function() {
        if (this.value) {
            const jt = new Date(this.value);
            jt.setDate(jt.getDate() - 7);
            const reminder = jt.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
            document.getElementById('reminderDate').placeholder = reminder;
        }
    });

    // Kalau ada error validasi, buka modal otomatis
    @if($errors->any())
        window.onload = () => bukaModal();
    @endif
</script>

</body>
</html>
