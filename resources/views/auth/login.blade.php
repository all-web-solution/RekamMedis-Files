<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Apotek Singkut Farma</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/native-ui-fixes.css') }}">
</head>
<body class="auth-page">
    <main class="auth-card">
        <div class="auth-card-header">
            <div class="auth-logo">
                <i class="fas fa-hospital-user"></i>
            </div>
            <h1>Aplikasi Apotek Singkut Farma</h1>
            <p>Masuk untuk mengelola data pasien dan kunjungan.</p>
        </div>

        <div class="auth-card-body">
            @if ($errors->any())
                <div class="auth-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" autocomplete="off">
                @csrf

                <div class="auth-form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="auth-input"
                        placeholder="Masukan email"
                        required
                        autofocus
                        autocomplete="email"
                    >
                </div>

                <div class="auth-form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth-input"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <label class="auth-checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya</span>
                </label>

                <button type="submit" class="auth-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </button>
            </form>
        </div>
    </main>
</body>
</html>
