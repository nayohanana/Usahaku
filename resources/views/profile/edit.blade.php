@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
    }
    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin: 0 auto;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-upload {
        position: relative;
        cursor: pointer;
    }
    .avatar-upload input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Edit Profile</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Edit Profile -->
        <div class="col-lg-7">
            <div class="card profile-card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-user-edit me-2 text-primary"></i>Edit Profile
                    </h5>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar -->
                        <div class="text-center mb-4">
                            <div class="avatar-wrapper avatar-upload">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" id="avatarPreview" alt="{{ $user->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=1a2a4a&color=fff&size=120" id="avatarPreview" alt="{{ $user->name }}">
                                @endif
                                <input type="file" name="avatar" id="avatarInput" accept="image/*">
                            </div>
                            <small class="text-muted">Klik foto untuk upload avatar baru (max 2MB)</small>
                            @error('avatar')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ganti Password -->
        <div class="col-lg-5">
            <div class="card profile-card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-key me-2 text-danger"></i>Ganti Password
                    </h5>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" placeholder="Masukkan password lama" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" placeholder="Minimal 8 karakter" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   name="new_password_confirmation" placeholder="Ulangi password baru" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');

    avatarInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
@endsection