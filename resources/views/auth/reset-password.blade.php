<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - UsahaKu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f4f8;
            padding: 20px;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(26,42,74,0.05) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 50%, rgba(34,197,94,0.05) 0%, transparent 60%);
            z-index: 0;
        }
        .wrapper { width: 100%; max-width: 440px; position: relative; z-index: 1; }
        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 44px 36px 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.06);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand .logo {
            display: inline-flex;
            width: 56px; height: 56px;
            background: #1a2a4a;
            border-radius: 14px;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .brand .logo i { font-size: 26px; color: white; }
        .brand h1 { font-size: 26px; font-weight: 700; color: #0f172a; }
        .brand h1 span { color: #22c55e; }
        .brand p { color: #94a3b8; font-size: 14px; margin-top: 4px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { font-size: 13px; font-weight: 600; color: #1e293b; display: block; margin-bottom: 6px; }
        .input-wrapper { position: relative; }
        .input-wrapper .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 2;
        }
        .input-wrapper .form-control {
            padding: 11px 16px 11px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background: #f8fafc;
            width: 100%;
            transition: all 0.25s;
        }
        .input-wrapper .form-control:focus {
            outline: none;
            border-color: #1a2a4a;
            background: white;
            box-shadow: 0 0 0 4px rgba(26,42,74,0.06);
        }
        .input-wrapper .form-control:disabled,
        .input-wrapper .form-control[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }
        .input-wrapper .toggle-password {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
        }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #1a2a4a;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background: #2a3a6a;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(26,42,74,0.18);
        }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-back:hover { color: #1a2a4a; text-decoration: underline; }
        .alert-custom {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .alert-custom.alert-danger { background: #fef2f2; color: #dc2626; }
        .alert-custom.alert-success { background: #f0fdf4; color: #16a34a; }
        .spinner {
            display: inline-block;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .footer { text-align: center; margin-top: 20px; font-size: 13px; color: #94a3b8; }
        .footer a { color: #1a2a4a; text-decoration: none; font-weight: 600; }
        @media (max-width: 480px) { .card { padding: 32px 20px 24px; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">
                <div class="logo"><i class="fas fa-store"></i></div>
                <h1>Usaha<span>Ku</span></h1>
                <p>Buat password baru</p>
            </div>

            @if(session('error'))
                <div class="alert-custom alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-custom alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ $email ?? session('reset_email') }}" 
                               placeholder="adminutama@gmail.com" required>
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 8 karakter" required>
                        <button type="button" class="toggle-password" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fas fa-check"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ulangi password baru" required>
                        <button type="button" class="toggle-password" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-key me-2"></i>Reset Password
                </button>

                <a href="{{ route('login') }}" class="btn-back">← Kembali ke Login</a>
            </form>

            <div class="footer">
                &copy; {{ date('Y') }} <a href="#">UsahaKu</a>. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.parentElement.querySelector('input');
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            btn.querySelector('i').classList.toggle('fa-eye');
            btn.querySelector('i').classList.toggle('fa-eye-slash');
        }

        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Memproses...';
        });
    </script>
</body>
</html>