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
<body class="simanta-auth auth-welcome">

    <!-- ===== BARIS LOGO MITRA ===== -->
    <div class="auth-partners">
        <div class="partner-logo">
           <img class="partner-logo-img" src="../asset/img/di.png">
        </div>

        <div class="partner-logo">
            <img class="partner-logo-img" src="../asset/img/id.png">
        </div>

         <div class="partner-logo">
            <img class="partner-logo-img" src="../asset/img/si.png">
        </div>
    </div>

    <!-- ===== HERO + CARD ===== -->
    <div class="hero-grid">

        <div class="hero-left">

            <h1>Selamat Datang di<br>Website <span>SIMANTA</span></h1>
            <div class="hero-underline"></div>
            <p>Sistem Manajemen Tagihan Terpadu PT Surveyor Indonesia yang mengintegrasikan seluruh proses monitoring, pengingat, dan pelaporan pembayaran dalam satu platform.</p>

            <div class="hero-feature-cards">
                <div class="hero-feature-card">
                    <div class="hero-feature-icon">@include('partials.icon', ['name' => 'activity'])</div>
                    <div class="label">Monitoring<br>Tagihan</div>
                </div>
                <div class="hero-feature-card">
                    <div class="hero-feature-icon">@include('partials.icon', ['name' => 'mail'])</div>
                    <div class="label">Email<br>Reminder</div>
                </div>
                <div class="hero-feature-card">
                    <div class="hero-feature-icon">@include('partials.icon', ['name' => 'file-text'])</div>
                    <div class="label">Laporan<br>Otomatis</div>
                </div>
            </div>
        </div>

        <div class="hero-right">

            <!-- Tab switcher: Login / Registrasi -->
            <div class="auth-wrapper">
                <div class="auth-tabs">
                    <a href="{{ route('login') }}" class="auth-tab active">Login</a>
                    <a href="{{ route('register') }}" class="auth-tab">Registrasi</a>
                </div>

                <div class="login-card">

                    <div class="card-title">
                        <h3>Login</h3>
                        <p>Masukkan email dan password Anda.</p>
                    </div>

                    @if ($errors->any())
                        <div class="error-box">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                     @csrf

                     <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-icon-group">
                            <span class="field-icon">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Masukkan alamat email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>
                     </div>

                     <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon-group">
                            <span class="field-icon">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="has-toggle"
                                placeholder="Masukkan password"
                                required
                            >
                            <button type="button" class="toggle-password" data-target="password" aria-label="Tampilkan password">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                     </div>

                     <div class="form-footer">
                        <label class="remember">
                            <input type="checkbox" name="remember"> Ingat Saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                        @endif
                     </div>

                     <button type="submit" class="btn-login">Login</button>

                    </form>

                    <div class="card-footer">
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang ›</a></p>
                    </div>

                </div>
            </div>
            

        </div>

    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.dataset.target);
                var isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    </script>

</body>
</html>
