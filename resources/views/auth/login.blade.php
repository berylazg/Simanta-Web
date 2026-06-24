<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
        }

        /* ===== SISI KIRI ===== */
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, #003a7a 0%, #005BAC 55%, #0077cc 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi lingkaran */
        .circle {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.08);
        }
        .c1 { width: 500px; height: 500px; top: -180px; right: -180px; }
        .c2 { width: 350px; height: 350px; bottom: -120px; left: -120px; }
        .c3 { width: 200px; height: 200px; top: 60%; right: 5%; }
        .c4 { width: 120px; height: 120px; top: 10%; left: 8%; }

        /* dot dekorasi */
        .dots {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .left-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 40px;
        }

        /* Logo besar */
        .big-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .big-logo-icon {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            backdrop-filter: blur(10px);
        }

        .big-logo h1 {
            color: white;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        .big-logo p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        /* 3 stat cards */
        .stat-cards {
            display: flex;
            gap: 16px;
        }

        .stat-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px;
            padding: 20px 24px;
            text-align: center;
            backdrop-filter: blur(10px);
            min-width: 110px;
        }

        .stat-card .icon {
            font-size: 22px;
            margin-bottom: 8px;
        }

        .stat-card .label {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            line-height: 1.4;
        }

        /* Tagline */
        .tagline h2 {
            color: white;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.5;
        }

        .tagline p {
            color: rgba(255,255,255,0.55);
            font-size: 13px;
            margin-top: 10px;
            line-height: 1.7;
        }

        .features {
            display: flex;
            gap: 20px;
            margin-top: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        .feature::before {
            content: '✓';
            color: #34d399;
            font-weight: 700;
        }

        /* ===== SISI KANAN ===== */
        .right-panel {
            width: 45%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .card-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .card-brand-icon {
            width: 48px;
            height: 48px;
            background: #005BAC;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
        }

        .card-brand-text h2 {
            color: #005BAC;
            font-size: 20px;
            font-weight: 700;
        }

        .card-brand-text p {
            color: #64748b;
            font-size: 11px;
            margin-top: 1px;
        }

        .card-title { margin-bottom: 24px; }

        .card-title h3 {
            color: #1e293b;
            font-size: 22px;
            font-weight: 700;
        }

        .card-title p {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #dc2626;
            font-size: 13px;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s;
            background: #f8fafc;
        }

        .form-group input:focus {
            border-color: #005BAC;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,91,172,0.1);
        }

        .form-group input::placeholder { color: #94a3b8; }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 13px;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #005BAC;
            cursor: pointer;
        }

        .forgot-link {
            color: #005BAC;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #005BAC;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-login:hover { background: #004a8f; }
        .btn-login:active { transform: scale(0.99); }

        .card-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .card-footer p {
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <!-- ===== SISI KIRI ===== -->
    <div class="left-panel">
        <div class="dots"></div>
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <div class="circle c3"></div>
        <div class="circle c4"></div>

        <div class="left-content">

            <div class="big-logo">
                <div class="big-logo-icon">📋</div>
                <h1>SIMANTA</h1>
                <p>Surveyor Indonesia Manajemen Tagihan</p>
            </div>

            <div class="stat-cards">
                <div class="stat-card">
                    <div class="icon">📊</div>
                    <div class="label">Monitoring<br>Tagihan</div>
                </div>
                <div class="stat-card">
                    <div class="icon">📧</div>
                    <div class="label">Email<br>Reminder</div>
                </div>
                <div class="stat-card">
                    <div class="icon">📄</div>
                    <div class="label">Laporan<br>Otomatis</div>
                </div>
            </div>

            <div class="tagline">
                <h2>Monitoring Tagihan Jatuh Tempo<br>& Email Reminder System</h2>
                <p>Sistem manajemen tagihan terpadu untuk PT Surveyor<br>
                   Indonesia Cabang Palembang. Monitor, kelola, dan kirim<br>
                   pengingat pembayaran secara otomatis.</p>
                <div class="features">
                    <span class="feature">Monitoring Otomatis</span>
                    <span class="feature">Email Reminder</span>
                    <span class="feature">Laporan Komprehensif</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== SISI KANAN ===== -->
    <div class="right-panel">
        <div class="login-card">

            <div class="card-brand">
                <div class="card-brand-icon">📋</div>
                <div class="card-brand-text">
                    <h2>SIMANTA</h2>
                    <p>Surveyor Indonesia Manajemen Tagihan</p>
                </div>
            </div>

            <div class="card-title">
                <h3>Masuk ke Sistem</h3>
                <p>Gunakan kredensial akun internal Anda</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">USERNAME</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email anda"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="form-footer">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Ingat Saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Masuk ke SIMANTA</button>

            </form>

            <div class="card-footer">
                <p>© 2024 PT Surveyor Indonesia Cabang Palembang<br>
                Sistem Informasi Internal — Akses Terbatas</p>
            </div>

        </div>
    </div>

</body>
</html>
