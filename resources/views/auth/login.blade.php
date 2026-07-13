<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">
</head>
<body class="simanta-auth auth-split">

    <!-- ===== SISI KIRI ===== -->
    <div class="left-panel">
        <div class="dots"></div>
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <div class="circle c3"></div>
        <div class="circle c4"></div>

        <div class="left-content">

            <div class="big-logo">
                <div class="big-logo-icon">@include('partials.icon', ['name' => 'building'])</div>
                <h1>SIMANTA</h1>
                <p>Surveyor Indonesia Manajemen Tagihan</p>
            </div>

            <div class="login-stat-cards">
                <div class="login-stat-card">
                    <div class="login-stat-icon">@include('partials.icon', ['name' => 'activity'])</div>
                    <div class="label">Monitoring<br>Tagihan</div>
                </div>
                <div class="login-stat-card">
                    <div class="login-stat-icon">@include('partials.icon', ['name' => 'mail'])</div>
                    <div class="label">Email<br>Reminder</div>
                </div>
                <div class="login-stat-card">
                    <div class="login-stat-icon">@include('partials.icon', ['name' => 'file-text'])</div>
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
            <div class="card-brand-icon">@include('partials.icon', ['name' => 'building'])</div>
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
