<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Reset Kata Sandi') }} - SIMANTA</title>
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
            <p>{{ __('PT Surveyor Indonesia Cabang Palembang') }}</p>
        </div>

        <div class="card-title">
            <h3>{{ __('Reset Kata Sandi') }}</h3>
            <p>{{ __('Buat kata sandi baru untuk akun Anda.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                           placeholder="nama@email.com" required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <div class="input-icon-group">
                    <span class="field-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input id="password" class="has-toggle" type="password" name="password"
                           placeholder="••••••••" required autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="password" aria-label="{{ __('Tampilkan password') }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <div class="input-icon-group">
                    <span class="field-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input id="password_confirmation" class="has-toggle" type="password" name="password_confirmation"
                           placeholder="••••••••" required autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="{{ __('Tampilkan password') }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                {{ __('Reset Password') }}
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

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-target'));
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>

</body>
</html>
