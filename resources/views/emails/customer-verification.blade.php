<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Tecomp99</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            margin-bottom: 10px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
            color: #4b5563;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            transform: translateY(-2px);
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #92400e;
        }
        .link-text {
            word-break: break-all;
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/logo-tecomp99.svg') }}" alt="Tecomp99 Logo">
            </div>
            <h1 class="title">Verifikasi Email Anda</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $customer->name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar di Tecomp99! Untuk mengaktifkan akun Anda dan mulai berbelanja, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">
                    ✓ Verifikasi Email Saya
                </a>
            </div>
            
            <p>Atau salin dan tempel link berikut ke browser Anda:</p>
            <div class="link-text">{{ $verificationUrl }}</div>
            
            <div class="warning">
                <strong>⚠️ Penting:</strong> Link verifikasi ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak meminta verifikasi ini, abaikan email ini.
            </div>
            
            <p>Setelah email Anda terverifikasi, Anda dapat:</p>
            <ul>
                <li>Masuk ke akun Tecomp99 Anda</li>
                <li>Berbelanja produk komputer dan IT terbaik</li>
                <li>Menggunakan layanan servis profesional kami</li>
                <li>Mendapatkan penawaran khusus dan promo menarik</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Tecomp99</strong><br>
            Solusi Komputer & IT Terpercaya</p>
            
            <p>Jika Anda mengalami masalah dengan tombol verifikasi, salin dan tempel link di atas ke browser Anda.</p>
            
            <p>Butuh bantuan? Hubungi kami di <a href="mailto:support@tecomp99.com" style="color: #f97316;">support@tecomp99.com</a></p>
            
            <p style="font-size: 12px; color: #9ca3af; margin-top: 20px;">
                Email ini dikirim secara otomatis. Mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
