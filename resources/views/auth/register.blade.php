<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - UsahaKu</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 20px;
        }
        .register-container { width: 100%; max-width: 480px; }
        .register-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
        }
        .register-brand { text-align: center; margin-bottom: 28px; }
        .register-brand h1 { font-size: 28px; font-weight: 800; color: #0f172a; }
        .register-brand h1 span { color: #22c55e; }
        .register-brand p { color: #94a3b8; font-size: 14px; margin-top: 4px; }
        .register-brand .icon-box {
            width: 56px; height: 56px;
            background: #1a2a4a;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .register-brand .icon-box i { font-size: 24px; color: white; }
        .form-label { font-weight: 600; font-size: 14px; color: #1e293b; }
        .form-control {
            border-radius: 12px;
            padding: 11px 16px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            transition: all 0.3s;
            background: #f8fafc;
        }
        .form-control:focus {
            border-color: #1a2a4a;
            box-shadow: 0 0 0 4px rgba(26,42,74,0.08);
            background: white;
        }
        .input-group { border-radius: 12px; overflow: hidden; }
        .input-group .form-control { border-radius: 12px 0 0 12px; }
        .input-group .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 11px 16px;
            color: #94a3b8;
        }
        .btn-register {
            width: 100%;
            padding: 13px;
            background: #1a2a4a;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background: #2a3a6a;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(26,42,74,0.2);
        }
        .btn-register i { margin-right: 8px; }
        .login-footer { text-align: center; margin-top: 20px; color: #94a3b8; font-size: 13px; }
        .login-footer a { color: #1a2a4a; text-decoration: none; font-weight: 600; }
        .login-footer a:hover { text-decoration: underline; }
        .alert-custom {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-custom.alert-danger { background: #fef2f2; color: #dc2626; }
        .alert-custom.alert-success { background: #f0fdf4; color: #16a34a; }
        @media (max-width: 480px) {
            .register-card { padding: 28px 20px; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-brand">
                <div class="icon-box"><i class="fas fa-store"></i></div>
                <h1>Usaha<span>Ku</span></h1>
                <p>Buat akun baru untuk mulai berjualan</p>
            </div>

            @if($errors->any())
                <div class="alert alert-custom alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama" required>
                    </div>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <div class="login-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>
</body>
</html>