<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Mencatat Pembayaran</title>
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

        .alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#16a34a; padding:14px 18px; border-radius:10px; margin-bottom:20px; font-size:13px; font-weight:500; }

        /* Pilih tagihan */
        .card { background:white; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:20px; overflow:hidden; }
        .card-body { padding:20px 24px; }
        .card-body h3 { color:#1e293b; font-size:15px; font-weight:700; margin-bottom:14px; }

        select.tagihan-select {
            width:100%; padding:12px 16px; border:2px solid #005BAC;
            border-radius:10px; font-size:14px; color:#374151;
            outline:none; background:#f8fafc; cursor:pointer;
        }

        /* Info tagihan */
        .info-card { background:white; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:20px; display:none; }
        .info-card.show { display:block; }
        .info-card-header { padding:14px 20px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
        .info-card-header h3 { color:#1e293b; font-size:14px; font-weight:700; }
        .info-body { padding:20px 24px; }
        .info-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:16px; }
        .info-item label { color:#94a3b8; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:4px; }
        .info-item p { color:#1e293b; font-size:13px; font-weight:600; }
        .total-nilai { color:#005BAC; font-size:28px; font-weight:800; }

        .badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-upcoming { background:#fef9c3; color:#ca8a04; }
        .badge-overdue  { background:#fee2e2; color:#dc2626; }
        .badge-draft    { background:#dbeafe; color:#2563eb; }

        /* Layout bawah */
        .bottom-grid { display:grid; grid-template-columns:1fr 320px; gap:20px; }

        /* Form pembayaran */
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { margin-bottom:16px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { display:block; color:#374151; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:7px; }
        .form-group label .req { color:#ef4444; }
        .form-group input, .form-group select, .form-group textarea {
            width:100%; padding:10px 14px; border:1.5px solid #e2e8f0;
            border-radius:9px; font-size:13px; color:#1e293b; outline:none;
            background:#f8fafc; font-family:'Segoe UI',sans-serif; transition:border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color:#005BAC; background:white;
        }
        .form-group input[readonly] { background:#f1f5f9; color:#64748b; }
        .form-group input::placeholder, .form-group textarea::placeholder { color:#94a3b8; }

        /* Upload area */
        .upload-area {
            border:2px dashed #cbd5e1; border-radius:10px; padding:28px;
            text-align:center; cursor:pointer; transition:all 0.2s; background:#f8fafc;
        }
        .upload-area:hover { border-color:#005BAC; background:#eff6ff; }
        .upload-area .upload-icon { font-size:28px; margin-bottom:8px; }
        .upload-area p { color:#64748b; font-size:13px; }
        .upload-area span { color:#005BAC; font-weight:600; cursor:pointer; }
        .upload-area small { color:#94a3b8; font-size:11px; display:block; margin-top:4px; }
        #fileInput { display:none; }

        .btn-simpan { width:100%; padding:13px; background:#10b981; color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; margin-top:8px; transition:background 0.2s; }
        .btn-simpan:hover { background:#059669; }

        /* Timeline */
        .timeline { padding:20px; }
        .timeline h3 { color:#1e293b; font-size:14px; font-weight:700; margin-bottom:20px; }
        .timeline-item { display:flex; gap:14px; margin-bottom:20px; position:relative; }
        .timeline-item:not(:last-child)::after {
            content:''; position:absolute; left:15px; top:34px;
            width:2px; height:calc(100% - 10px); background:#e2e8f0;
        }
        .tl-dot { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; z-index:1; }
        .tl-dot.done   { background:#dcfce7; border:2px solid #10b981; }
        .tl-dot.active { background:#dbeafe; border:2px solid #3b82f6; }
        .tl-dot.pending { background:#f1f5f9; border:2px solid #e2e8f0; }
        .tl-content h4 { color:#1e293b; font-size:13px; font-weight:600; }
        .tl-content p { color:#94a3b8; font-size:11px; margin-top:2px; }

        /* Empty state */
        .empty-state { text-align:center; padding:48px; color:#94a3b8; }
        .empty-state .icon { font-size:48px; margin-bottom:12px; }
        .empty-state h3 { color:#64748b; font-size:15px; margin-bottom:4px; }
        .empty-state p { font-size:13px; }
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
    <a href="/pembayaran" class="active">💳 Mencatat Pembayaran</a>
    <a href="/laporan">📄 Laporan</a>
    <div class="sidebar-footer">
        <p>{{ auth()->user()->name }}</p>
        <span>administrator</span>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div>
            <h2>Mencatat Pembayaran</h2>
            <p>Rekam pembayaran tagihan dan perbarui status</p>
        </div>

        <div class="topbar-right" style="display:flex;align-items:center;gap:12px;">
            <span class="topbar-date" style="color:#64748b;font-size:12px;">📅 {{ now()->translatedFormat('l, d F Y') }}</span>

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
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- PILIH TAGIHAN --}}
        <div class="card">
            <div class="card-body">
                <h3>Pilih Tagihan yang Akan Dibayar</h3>
                <select class="tagihan-select" id="tagihanSelect" onchange="loadTagihan(this.value)">
                    <option value="">— Pilih tagihan yang akan dicatat pembayarannya —</option>
                    @foreach($tagihans as $t)
                        <option value="{{ $t->id }}"
                            data-invoice="{{ $t->nomor_invoice }}"
                            data-nama="{{ $t->nama_tagihan }}"
                            data-vendor="{{ $t->vendor->nama_vendor ?? '-' }}"
                            data-kategori="{{ $t->kategori->nama_kategori ?? '-' }}"
                            data-jatuh="{{ $t->tanggal_jatuh_tempo ? $t->tanggal_jatuh_tempo->format('d M Y') : '-' }}"
                            data-nominal="{{ $t->nominal }}"
                            data-status="{{ $t->status }}"
                            data-created="{{ $t->created_at ? $t->created_at->format('d M Y') : '-' }}"
                            data-reminder="{{ $t->tanggal_reminder ? \Carbon\Carbon::parse($t->tanggal_reminder)->format('d M Y') : '-' }}">
                            {{ $t->nomor_invoice }} · {{ Str::limit($t->nama_tagihan, 45) }} · Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- EMPTY STATE --}}
        <div id="emptyState">
            <div class="card">
                <div class="empty-state">
                    <div class="icon">💳</div>
                    <h3>Pilih tagihan untuk mencatat pembayaran</h3>
                    <p>{{ $tagihans->count() }} tagihan menunggu pembayaran</p>
                </div>
            </div>
        </div>

        {{-- INFO TAGIHAN --}}
        <div class="info-card" id="infoCard">
            <div class="info-card-header">
                <h3>Informasi Tagihan</h3>
                <span id="statusBadge" class="badge"></span>
            </div>
            <div class="info-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>No. Invoice</label>
                        <p id="infoInvoice">-</p>
                    </div>
                    <div class="info-item">
                        <label>Vendor</label>
                        <p id="infoVendor">-</p>
                    </div>
                    <div class="info-item">
                        <label>Kategori</label>
                        <p id="infoKategori">-</p>
                    </div>
                    <div class="info-item">
                        <label>Jatuh Tempo</label>
                        <p id="infoJatuh">-</p>
                    </div>
                </div>
                <div>
                    <label style="color:#94a3b8;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Total Tagihan</label>
                    <div class="total-nilai" id="infoNominal">-</div>
                </div>
            </div>
        </div>

        {{-- FORM + TIMELINE --}}
        <div class="bottom-grid" id="formSection" style="display:none;">

            {{-- FORM PEMBAYARAN --}}
            <div class="card">
                <div class="card-body">
                    <h3 style="margin-bottom:20px;">Form Pembayaran</h3>
                    <form method="POST" action="{{ route('pembayaran.store') }}">
                        @csrf
                        <input type="hidden" name="tagihan_id" id="tagihanIdInput">
                        <input type="hidden" name="jumlah_bayar" id="jumlahBayarInput">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>No. Invoice</label>
                                <input type="text" id="formInvoice" readonly>
                            </div>
                            <div class="form-group">
                                <label>Vendor</label>
                                <input type="text" id="formVendor" readonly>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Bayar <span class="req">*</span></label>
                                <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Metode Pembayaran <span class="req">*</span></label>
                                <select name="metode_bayar" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="tunai">Tunai</option>
                                    <option value="cek">Cek</option>
                                    <option value="giro">Giro</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group full">
                                <label>Nomor Referensi</label>
                                <input type="text" name="nomor_referensi" placeholder="contoh: TRF202606290001">
                            </div>
                        </div>

                        {{-- Upload Bukti --}}
                        <div class="form-group">
                            <label>Upload Bukti Pembayaran</label>
                            <div class="upload-area" onclick="document.getElementById('fileInput').click()"
                                ondragover="event.preventDefault();this.style.borderColor='#005BAC'"
                                ondragleave="this.style.borderColor='#cbd5e1'"
                                ondrop="handleDrop(event)">
                                <div class="upload-icon">📎</div>
                                <p>Drag & drop atau <span>browse file</span></p>
                                <small>PNG, JPG, PDF · Maks 5MB</small>
                                <p id="fileName" style="color:#005BAC;font-size:12px;margin-top:6px;"></p>
                            </div>
                            <input type="file" id="fileInput" name="file_bukti" accept=".png,.jpg,.jpeg,.pdf" onchange="showFileName(this)">
                        </div>

                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" rows="3" placeholder="Catatan tambahan mengenai pembayaran ini..."></textarea>
                        </div>

                        <button type="submit" class="btn-simpan">✔ Simpan Pembayaran</button>
                    </form>
                </div>
            </div>

            {{-- TIMELINE --}}
            <div class="card">
                <div class="timeline">
                    <h3>Timeline Tagihan</h3>

                    <div class="timeline-item">
                        <div class="tl-dot done">📄</div>
                        <div class="tl-content">
                            <h4>Invoice Dibuat</h4>
                            <p id="tlCreated">-</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="tl-dot active" id="tlReminderDot">📧</div>
                        <div class="tl-content">
                            <h4>Reminder Terkirim</h4>
                            <p id="tlReminder">-</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="tl-dot pending" id="tlPayDot">💳</div>
                        <div class="tl-content">
                            <h4>Pembayaran Dicatat</h4>
                            <p id="tlPay">Belum dicatat</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="tl-dot pending">✅</div>
                        <div class="tl-content">
                            <h4>Selesai</h4>
                            <p>—</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end bottom-grid --}}

    </div>
</div>

<script>
function loadTagihan(id) {
    if (!id) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('infoCard').classList.remove('show');
        document.getElementById('formSection').style.display = 'none';
        return;
    }

    const opt = document.querySelector(`#tagihanSelect option[value="${id}"]`);

    // Isi info card
    document.getElementById('infoInvoice').textContent = opt.dataset.invoice;
    document.getElementById('infoVendor').textContent   = opt.dataset.vendor;
    document.getElementById('infoKategori').textContent = opt.dataset.kategori;
    document.getElementById('infoJatuh').textContent    = opt.dataset.jatuh;
    document.getElementById('infoNominal').textContent  = 'Rp ' + parseInt(opt.dataset.nominal).toLocaleString('id-ID');

    // Status badge
    const badge = document.getElementById('statusBadge');
    const statusMap = { upcoming:'Akan Jatuh Tempo', overdue:'Terlambat', draft:'Draft' };
    const classMap  = { upcoming:'badge-upcoming', overdue:'badge-overdue', draft:'badge-draft' };
    badge.textContent = statusMap[opt.dataset.status] || opt.dataset.status;
    badge.className   = 'badge ' + (classMap[opt.dataset.status] || 'badge-draft');

    // Isi form
    document.getElementById('tagihanIdInput').value = id;
    document.getElementById('jumlahBayarInput').value = opt.dataset.nominal;
    document.getElementById('formInvoice').value = opt.dataset.invoice;
    document.getElementById('formVendor').value  = opt.dataset.vendor;

    // Timeline
    document.getElementById('tlCreated').textContent  = opt.dataset.created;
    document.getElementById('tlReminder').textContent = opt.dataset.reminder !== '-' ? opt.dataset.reminder : 'Belum terkirim';

    // Tampilkan
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('infoCard').classList.add('show');
    document.getElementById('formSection').style.display = 'grid';
}

function showFileName(input) {
    if (input.files[0]) {
        document.getElementById('fileName').textContent = '📎 ' + input.files[0].name;
    }
}

function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('fileInput').files = e.dataTransfer.files;
        document.getElementById('fileName').textContent = '📎 ' + file.name;
    }
    e.target.closest('.upload-area').style.borderColor = '#cbd5e1';
}
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
