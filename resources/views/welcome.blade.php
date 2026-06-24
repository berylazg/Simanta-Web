<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Surveyor Indonesia Manajemen Tagihan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== NAVBAR ===== */
        nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 60px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            background: #005BAC;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .nav-brand h1 {
            color: #005BAC;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .nav-brand span {
            color: #64748b;
            font-size: 12px;
            margin-left: 4px;
            font-weight: 400;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-outline {
            padding: 8px 22px;
            border: 1.5px solid #005BAC;
            color: #005BAC;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #005BAC;
            color: white;
        }

        .btn-solid {
            padding: 8px 22px;
            background: #005BAC;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-solid:hover { background: #004a8f; }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, #003a7a 0%, #005BAC 55%, #0077cc 100%);
            padding: 90px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero .dots {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.07);
        }
        .c1 { width: 500px; height: 500px; top: -200px; right: -150px; }
        .c2 { width: 300px; height: 300px; bottom: -100px; left: -80px; }

        .hero-content { position: relative; z-index: 1; max-width: 700px; margin: 0 auto; }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.9);
            font-size: 12px;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
        }

        .hero h2 {
            color: white;
            font-size: 46px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero h2 span { color: #60b4ff; }

        .hero p {
            color: rgba(255,255,255,0.7);
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .hero-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 14px 36px;
            background: white;
            color: #005BAC;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .btn-hero-secondary {
            padding: 14px 36px;
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-hero-secondary:hover { background: rgba(255,255,255,0.25); }

        /* ===== STATS BAR ===== */
        .stats-bar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 28px 60px;
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .stat-item { text-align: center; }

        .stat-item .val {
            color: #005BAC;
            font-size: 28px;
            font-weight: 800;
        }

        .stat-item .desc {
            color: #64748b;
            font-size: 12px;
            margin-top: 2px;
        }

        /* ===== FITUR ===== */
        .section {
            padding: 70px 60px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-title h3 {
            color: #1e293b;
            font-size: 30px;
            font-weight: 700;
        }

        .section-title p {
            color: #64748b;
            font-size: 14px;
            margin-top: 8px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 32px 28px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .feature-card:hover {
            border-color: #005BAC;
            box-shadow: 0 4px 20px rgba(0,91,172,0.1);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            background: #eff6ff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
        }

        .feature-card h4 {
            color: #1e293b;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
        }

        /* ===== FOOTER ===== */
        footer {
            background: #1e293b;
            color: rgba(255,255,255,0.5);
            text-align: center;
            padding: 24px;
            font-size: 12px;
            margin-top: auto;
        }

        footer span { color: #60b4ff; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <div class="nav-brand">
            <div class="nav-brand-icon">📋</div>
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
                <div class="feature-icon">📊</div>
                <h4>Monitoring Tagihan</h4>
                <p>Pantau status semua tagihan operasional secara real-time. Filter berdasarkan vendor, kategori, dan status pembayaran.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📧</div>
                <h4>Email Reminder Otomatis</h4>
                <p>Sistem mengirim email pengingat secara otomatis sebelum tagihan jatuh tempo, sehingga tidak ada yang terlewat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h4>Pencatatan Pembayaran</h4>
                <p>Rekam setiap pembayaran lengkap dengan bukti transfer, metode bayar, dan nomor referensi transaksi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📁</div>
                <h4>Kelola Data Tagihan</h4>
                <p>Tambah, edit, dan hapus data tagihan dengan mudah. Lengkap dengan upload file invoice PDF dari vendor.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📄</div>
                <h4>Laporan Komprehensif</h4>
                <p>Generate laporan pengeluaran bulanan dan ekspor ke PDF atau Excel untuk keperluan pelaporan internal.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔔</div>
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
