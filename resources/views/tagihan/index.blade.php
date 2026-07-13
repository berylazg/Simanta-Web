<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMANTA — Kelola Data Tagihan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-app">

@include('partials.sidebar', ['active' => 'tagihan'])

{{-- MAIN --}}
<div class="main">

    @include('partials.topbar', [
        'title' => 'Kelola Data Tagihan',
        'subtitle' => 'Tambah, ubah, dan hapus data tagihan operasional',
    ])

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
                    @include('partials.icon', ['name' => 'search'])
                    <input type="text" name="search" placeholder="Cari no. invoice atau nama tagihan..." value="{{ request('search') }}">
                </div>
            </form>
            <button class="btn-primary" onclick="bukaModal()">
                @include('partials.icon', ['name' => 'plus'])
                Tambah Tagihan
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
                        <th width="120">Aksi</th>
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
                        <td>

                            <button
                                type="button"
                                class="btn-edit"
                                data-id="{{ $t->id }}"
                                data-invoice="{{ $t->nomor_invoice }}"
                                data-kontrak="{{ $t->nomor_kontrak }}"
                                data-nama="{{ $t->nama_tagihan }}"
                                data-vendor="{{ $t->nama_vendor }}"
                                data-kategori="{{ $t->kategori_id }}"
                                data-nominal="{{ $t->nominal }}"
                                data-invoice_date="{{ $t->tanggal_invoice }}"
                                data-jt="{{ $t->tanggal_jatuh_tempo }}"
                                data-status="{{ $t->status }}"
                                data-deskripsi="{{ $t->deskripsi }}"
                                onclick="editTagihan(this)">

                                @include('partials.icon', ['name' => 'edit'])

                            </button>

                            <form
                                action="{{ route('tagihan.destroy',$t->id) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn-delete"
                                    onclick="return confirm('Hapus tagihan ini?')">

                                    @include('partials.icon', ['name' => 'trash'])

                                </button>

                            </form>

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

        <form id="formTagihan" method="POST" action="{{ route('tagihan.store') }}">
            @csrf
            <div class="modal-body">

                {{-- Baris 1: Nomor Invoice + Nomor Kontrak --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nomor Invoice <span class="required">*</span></label>
                        <input type="text" id="nomor_invoice" name="nomor_invoice" value="{{ old('nomor_invoice', $nextInvoice) }}" placeholder="INV-2026-019" required>
                        @error('nomor_invoice')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Kontrak</label>
                        <input type="text" id="nomor_kontrak" name="nomor_kontrak" value="{{ old('nomor_kontrak') }}" placeholder="KTR/2026/XXX/001">
                    </div>
                </div>

                {{-- Baris 2: Nama Tagihan --}}
                <div class="form-row full">
                    <div class="form-group">
                        <label>Nama Tagihan <span class="required">*</span></label>
                        <input type="text" id="nama_tagihan" name="nama_tagihan" value="{{ old('nama_tagihan') }}" placeholder="contoh: Sewa Kendaraan Operasional — Januari 2026" required>
                        @error('nama_tagihan')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 3: Vendor + Kategori --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Vendor</label>

                        <input
                            type="text"
                            name="nama_vendor"
                            value="{{ old('nama_vendor') }}"
                            placeholder="Masukkan nama vendor"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <select id="kategori_id" name="kategori_id" required>
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
                            <input type="number" id="nominal" name="nominal" value="{{ old('nominal') }}" placeholder="Contoh: 5000000" min="0" required>
                        </div>
                        @error('nominal')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Baris 5: Tanggal Invoice + Jatuh Tempo + Reminder --}}
                <div class="form-row three">
                    <div class="form-group">
                        <label>Tanggal Invoice</label>
                        <input type="date" id="tanggal_invoice" name="tanggal_invoice" value="{{ old('tanggal_invoice') }}">
                        @error('tanggal_invoice')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Jatuh Tempo <span class="required">*</span></label>
                        <input type="date" id="jatuhTempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" required>
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
                        <select id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="draft"    {{ old('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Akan Jatuh Tempo</option>
                            <option value="paid"     {{ old('status') == 'paid'     ? 'selected' : '' }}>Lunas</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        @error('status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row full">
    <div class="form-group">
        <label>Upload Invoice (PDF)</label>

        <input
            type="file"
            id="file_invoice"
            name="file_invoice"
            accept=".pdf,application/pdf">

                <div class="hint">
                    Format PDF • Maksimal 10 MB
                </div>

                @error('file_invoice')
                    <div class="field-error">{{ $message }}</div>
                @enderror
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
        function bukaModal(){

            document.getElementById('formTagihan').action =
                "{{ route('tagihan.store') }}";

            let method=document.getElementById('methodPUT');

            if(method){
                method.remove();
            }

            document.getElementById('formTagihan').reset();

            document.getElementById('modalOverlay').classList.add('active');

            document.body.style.overflow='hidden';

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

@include('partials.notif-script')

<script>
function editTagihan(btn){

    bukaModal();

    document.getElementById('formTagihan').action =
        "/tagihan/"+btn.dataset.id;

    if(!document.getElementById('methodPUT')){

        let method=document.createElement('input');

        method.type='hidden';

        method.name='_method';

        method.value='PUT';

        method.id='methodPUT';

        document.getElementById('formTagihan').appendChild(method);

    }

    document.getElementById('nomor_invoice').value=btn.dataset.invoice;

    document.getElementById('nomor_kontrak').value=btn.dataset.kontrak;

    document.getElementById('nama_tagihan').value=btn.dataset.nama;

    document.getElementById('nama_vendor').value=btn.dataset.vendor;

    document.getElementById('kategori_id').value=btn.dataset.kategori;

    document.getElementById('nominal').value=btn.dataset.nominal;

    document.getElementById('tanggal_invoice').value=btn.dataset.invoice_date;

    document.getElementById('jatuhTempo').value=btn.dataset.jt;

    document.getElementById('status').value=btn.dataset.status;

    document.getElementById('deskripsi').value=btn.dataset.deskripsi;

}
</script>

</body>
</html>