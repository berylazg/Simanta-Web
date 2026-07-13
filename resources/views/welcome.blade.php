<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Surveyor Indonesia Manajemen Tagihan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-landing">

    <!-- NAVBAR -->
    <nav>
        <div class="nav-brand">
            <div class="nav-brand-icon">@include('partials.icon', ['name' => 'building'])</div>
            <div>
                <h1>SIMANTA <span>by PT Surveyor Indonesia</span></h1>
            </div>
        </div>
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-outline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-solid">Register</a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <div class="hero">
        <div class="dots"></div>
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <div class="hero-content">
            <div class="hero-badge">🏢 PT Surveyor Indonesia Cabang Palembang</div>
            <h2>Sistem Manajemen<br><span>Tagihan Operasional</span></h2>
            <p>Monitor tagihan jatuh tempo, kelola pembayaran, dan kirim<br>
               email reminder secara otomatis — semua dalam satu sistem.</p>
            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-hero-primary">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-hero-secondary">Register
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- FITUR -->
    <div class="section">
        <div class="section-title">
            <h3>Fitur Utama SIMANTA</h3>
            <p>Semua yang dibutuhkan untuk mengelola tagihan operasional perusahaan</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'activity'])</div>
                <h4>Monitoring Tagihan</h4>
                <p>Pantau status semua tagihan operasional secara real-time. Filter berdasarkan vendor, kategori, dan status pembayaran.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'mail'])</div>
                <h4>Email Reminder Otomatis</h4>
                <p>Sistem mengirim email pengingat secara otomatis sebelum tagihan jatuh tempo, sehingga tidak ada yang terlewat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'credit-card'])</div>
                <h4>Pencatatan Pembayaran</h4>
                <p>Rekam setiap pembayaran lengkap dengan bukti transfer, metode bayar, dan nomor referensi transaksi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'database'])</div>
                <h4>Kelola Data Tagihan</h4>
                <p>Tambah, edit, dan hapus data tagihan dengan mudah. Lengkap dengan upload file invoice PDF dari vendor.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'file-text'])</div>
                <h4>Laporan Komprehensif</h4>
                <p>Generate laporan pengeluaran bulanan dan ekspor ke PDF atau Excel untuk keperluan pelaporan internal.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">@include('partials.icon', ['name' => 'bell'])</div>
                <h4>Notifikasi Status</h4>
                <p>Dapatkan notifikasi real-time untuk tagihan yang akan jatuh tempo atau sudah melewati batas waktu pembayaran.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>© 2024 <span>PT Surveyor Indonesia Cabang Palembang</span> — SIMANTA v1.0 · Sistem Informasi Internal · Akses Terbatas</p>
    </footer>

</body>
</html>
