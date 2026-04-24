@extends('layout.master')

@section('page-title', 'Edit Profile')
@section('page-description', 'Kelola informasi akun dan password pengguna')

@section('content')
    @if (session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    <div class="profile-page-grid">
        <div class="native-card profile-summary-card">
            <div class="profile-avatar-large">
                <i class="fas fa-user"></i>
            </div>

            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>

            <div class="profile-meta-list">
                <div class="profile-meta-item">
                    <span>Status Akun</span>
                    <strong>Aktif</strong>
                </div>

                <div class="profile-meta-item">
                    <span>Terdaftar</span>
                    <strong>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '-' }}</strong>
                </div>

                <div class="profile-meta-item">
                    <span>Update Terakhir</span>
                    <strong>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') : '-' }}</strong>
                </div>
            </div>
        </div>

        <div>
            <div class="native-card">
                <div class="native-card-header">
                    <div>
                        <h3 class="native-card-title">Informasi Profile</h3>
                        <p class="native-card-subtitle">Perbarui nama dan email akun login.</p>
                    </div>
                </div>

                @if ($errors->profile->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <span>{{ $errors->profile->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="native-form-grid">
                        <div class="input-group-custom">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="input-group-custom">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="native-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan Profile
                        </button>
                    </div>
                </form>
            </div>

            <div class="native-card">
                <div class="native-card-header">
                    <div>
                        <h3 class="native-card-title">Ubah Password</h3>
                        <p class="native-card-subtitle">Gunakan password minimal 8 karakter, berisi huruf dan angka.</p>
                    </div>
                </div>

                @if ($errors->password->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <span>{{ $errors->password->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="native-form-grid">
                        <div class="input-group-custom">
                            <label><i class="fas fa-lock"></i> Password Lama</label>
                            <input type="password" name="current_password" required>
                        </div>

                        <div class="input-group-custom">
                            <label><i class="fas fa-key"></i> Password Baru</label>
                            <input type="password" name="password" required>
                        </div>

                        <div class="input-group-custom">
                            <label><i class="fas fa-check"></i> Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="native-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
