<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test Email SIMANTA</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">

<div style="max-width:650px;margin:30px auto;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);">

    <div style="background:#005BAC;padding:24px;text-align:center;">
        <h1 style="color:white;margin:0;">📋 SIMANTA</h1>
        <p style="color:rgba(255,255,255,.7);margin-top:5px;">
            Surveyor Indonesia Manajemen Tagihan
        </p>
    </div>

    <div style="padding:30px;">

        <h2 style="color:#1e293b;">
            Email Percobaan Berhasil
        </h2>

        <p>
            Halo Admin,
        </p>

        <p>
            Jika Anda menerima email ini berarti konfigurasi email pada
            <strong>SIMANTA</strong> telah berhasil.
        </p>

        <div style="
            background:#ecfdf5;
            border:1px solid #10b981;
            padding:18px;
            border-radius:8px;
            margin:25px 0;
        ">

            <strong style="color:#047857;">
                ✓ SMTP berhasil terhubung
            </strong>

            <br><br>

            Sistem siap mengirim reminder tagihan secara otomatis.

        </div>

        <table style="width:100%;margin-top:20px;">

            <tr>
                <td width="180"><strong>Tanggal Test</strong></td>
                <td>{{ now()->format('d F Y H:i') }}</td>
            </tr>

            <tr>
                <td><strong>Status</strong></td>
                <td style="color:green;">Berhasil</td>
            </tr>

        </table>

        <br>

        <p>
            Terima kasih.
        </p>

    </div>

    <div style="background:#f8fafc;padding:20px;text-align:center;">
        <small style="color:#64748b;">
            Email otomatis dari sistem SIMANTA
        </small>
    </div>

</div>

</body>
</html>