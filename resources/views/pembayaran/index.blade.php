<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Mencatat Pembayaran</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-app">

@include('partials.sidebar', ['active' => 'pembayaran'])

<div class="main">
    @include('partials.topbar', [
        'title' => 'Mencatat Pembayaran',
        'subtitle' => 'Rekam pembayaran tagihan dan perbarui status',
    ])

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
                            data-vendor="{{ $t->nama_vendor ?? '-' }}"
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

@include('partials.notif-script')

</body>
</html>