<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Segoe UI', sans-serif; background:#f1f5f9; margin:0; padding:24px;">
    <div style="max-width:520px;margin:0 auto;background:white;border-radius:12px;overflow:hidden;">

        {{-- Header --}}
        <div style="background:#005BAC;padding:24px;text-align:center;">
            <h1 style="color:white;font-size:20px;margin:0;">📋 SIMANTA</h1>
            <p style="color:rgba(255,255,255,0.7);font-size:11px;margin:4px 0 0;">Surveyor Indonesia Manajemen Tagihan</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 24px;">

            @if($tagihan->status === 'overdue')
                <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;">
                    <p style="color:#dc2626;font-size:13px;font-weight:700;margin:0;">⚠️ TAGIHAN INI SUDAH TERLAMBAT</p>
                </div>
            @else
                <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:20px;">
                    <p style="color:#ca8a04;font-size:13px;font-weight:700;margin:0;">🔔 TAGIHAN AKAN SEGERA JATUH TEMPO</p>
                </div>
            @endif

            <p style="color:#374151;font-size:14px;">Yth. Admin SIMANTA,</p>
            <p style="color:#374151;font-size:14px;">Berikut detail tagihan yang perlu segera ditindaklanjuti:</p>

            <table style="width:100%;margin:20px 0;border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0;color:#94a3b8;font-size:12px;width:140px;">No. Invoice</td>
                    <td style="padding:8px 0;color:#1e293b;font-size:13px;font-weight:600;">{{ $tagihan->nomor_invoice }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#94a3b8;font-size:12px;">Nama Tagihan</td>
                    <td style="padding:8px 0;color:#1e293b;font-size:13px;font-weight:600;">{{ $tagihan->nama_tagihan }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#94a3b8;font-size:12px;">Vendor</td>
                    <td style="padding:8px 0;color:#1e293b;font-size:13px;font-weight:600;">{{ $tagihan->vendor->nama_vendor ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#94a3b8;font-size:12px;">Jatuh Tempo</td>
                    <td style="padding:8px 0;color:#1e293b;font-size:13px;font-weight:600;">{{ $tagihan->tanggal_jatuh_tempo->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#94a3b8;font-size:12px;">Nominal</td>
                    <td style="padding:8px 0;color:#005BAC;font-size:16px;font-weight:800;">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                </tr>
            </table>

            <p style="color:#64748b;font-size:12px;margin-top:24px;">
                Silakan login ke sistem SIMANTA untuk mencatat pembayaran tagihan ini.
            </p>

        </div>

        {{-- Footer --}}
        <div style="background:#f8fafc;padding:16px 24px;text-align:center;border-top:1px solid #f1f5f9;">
            <p style="color:#94a3b8;font-size:11px;margin:0;">
                Email otomatis dari sistem SIMANTA<br>
                PT Surveyor Indonesia Cabang Palembang
            </p>
        </div>

    </div>
</body>
</html>
