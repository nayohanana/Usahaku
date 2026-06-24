<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #1a2a4a; padding-bottom: 20px; }
        .header h1 { color: #1a2a4a; margin: 0; }
        .header h1 span { color: #22c55e; }
        .otp-code { font-size: 40px; font-weight: bold; text-align: center; padding: 20px; background: #f1f5f9; border-radius: 8px; letter-spacing: 8px; color: #1a2a4a; margin: 20px 0; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 20px; }
        .warning { color: #ef4444; font-size: 13px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Usaha<span>Ku</span></h1>
            <p style="color: #64748b;">Reset Password</p>
        </div>

        <p>Halo,</p>
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>

        <div class="otp-code">{{ $otp }}</div>

        <p style="text-align: center; color: #64748b;">Masukkan kode di atas untuk mereset password Anda.</p>
        <p class="warning">Kode ini hanya berlaku selama 5 menit.</p>

        <div class="footer">
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>&copy; {{ date('Y') }} UsahaKu. All rights reserved.</p>
        </div>
    </div>
</body>
</html>