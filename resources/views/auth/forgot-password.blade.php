<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - SIMANTA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/simanta.css') }}">

</head>
<body class="simanta-auth auth-center">

    <div class="dots"></div>
    <div class="circle c1"></div>
    <div class="circle c2"></div>

    <div class="login-card">

        <div class="card-header">
            <div class="logo">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2>SIMANTA</h2>
            <p>PT Surveyor Indonesia Cabang Palembang</p>
        </div>
        
        <div class="card-title">
            <h3>Lupa Kata Sandi?</h3>
            <p>Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk membuat kata sandi baru.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-box">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <div class="input-icon-group">
                    <span class="field-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                {{ __('Kirim Link Reset Password') }}
            </button>
        </form>

        <div class="card-footer">
            <p>
                {{ __('Sudah ingat kata sandi Anda?') }}
                <a href="{{ route('login') }}">{{ __('Kembali ke Login') }}</a>
            </p>
        </div>
    </div>

    <div class="copyright">
        &copy; {{ date('Y') }} SIMANTA — PT Surveyor Indonesia Cabang Palembang
    </div>

</body>
</html>
