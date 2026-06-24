<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANTA — Daftar Akun</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #003a7a 0%, #005BAC 55%, #0077cc 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi background */
        .dots {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .circle {
            position: fixed;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.07);
            pointer-events: none;
        }
        .c1 { width: 500px; height: 500px; top: -180px; right: -150px; }
        .c2 { width: 300px; height: 300px; bottom: -100px; left: -80px; }

        /* Card */
        .register-card {
            background: white;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        /* Header card */
        .card-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .card-header .logo {
            width: 52px;
            height: 52px;
            background: #005BAC;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 14px;
        }

        .card-header h2 {
            color: #005BAC;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .card-header p {
            color: #64748b;
            font-size: 12px;
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0 0 24px;
        }

        .card-title {
            margin-bottom: 22px;
        }

        .card-title h3 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 700;
        }

        .card-title p {
            color: #64748b;
            font-size: 13px;
            margin-top: 3px;
        }

        /* Error */
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
            color: #dc2626;
            font-size: 13px;
        }

        /* Form */
        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            color: #374151;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #005BAC;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,91,172,0.1);
        }

        .form-group input::placeholder { color: #94a3b8; }

        /* Error per field */
        .field-error {
            color: #dc2626;
            font-size: 11px;
            margin-top: 5px;
        }

        /* Button */
        .btn-register {
            width: 100%;
            padding: 13px;
            background: #005BAC;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 6px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-register:hover { background: #004a8f; }
        .btn-register:active { transform: scale(0.99); }

        /* Footer card */
        .card-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
        }

        .card-footer p {
            color: #64748b;
            font-size: 13px;
        }

        .card-footer a {
            color: #005BAC;
            font-weight: 600;
            text-decoration: none;
        }

        .card-footer a:hover { text-decoration: underline; }

        .copyright {
            text-align: center;
            margin-top: 20px;
            color: rgba(255,255,255,0.4);
            font-size: 11px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    <div class="dots"></div>
    <div class="circle c1"></div>
    <div class="circle c2"></div>

    <div class="register-card">

        <div class="card-header">
            <div class="logo">📋</div>
            <h2>SIMANTA</h2>
            <p>Surveyor Indonesia Manajemen Tagihan</p>
        </div>

        <hr class="divider">

        <div class="card-title">
            <h3>Daftar Akun Baru</h3>
            <p>Isi data berikut untuk membuat akun internal</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Masukkan nama lengkap"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan alamat email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                >
            </div>

            <button type="submit" class="btn-register">Daftar Sekarang</button>

        </form>

        <div class="card-footer">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
        </div>

    </div>

    <div class="copyright">
        © 2024 PT Surveyor Indonesia Cabang Palembang — Sistem Informasi Internal
    </div>

</body>
</html>
