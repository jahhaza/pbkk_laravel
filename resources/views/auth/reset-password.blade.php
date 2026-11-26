<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur ulang kata sandi — Laravel Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=6">
</head>
<body class="auth-page">
    <main class="account-card">
        <div class="account-content">
            <div class="form-content">
                <span class="form-eyebrow">PULIHKAN AKUN</span>
                <h1>Kata sandi baru<span class="heading-dot">.</span></h1>
                <p class="form-intro">Gunakan kata sandi baru minimal 8 karakter.</p>
                @if ($errors->any())
                    <div class="notice error" role="alert">Periksa kembali data yang dimasukkan.</div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="field @error('email') server-error @enderror">
                        <label for="email">Email akun</label>
                        <input id="email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="nama@gmail.com" value="{{ old('email', request()->query('email')) }}" required>
                        @error('email') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="field @error('password') server-error @enderror">
                        <label for="password">Kata sandi baru</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                        @error('password') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="field @error('password_confirmation') server-error @enderror">
                        <label for="password_confirmation">Konfirmasi kata sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                        @error('password_confirmation') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <button class="primary-button" type="submit">Simpan kata sandi <span aria-hidden="true">→</span></button>
                </form>
                <div class="account-links"><a href="{{ route('login') }}">Kembali ke login</a></div>
            </div>
        </div>
        <div class="account-art" aria-hidden="true"></div>
    </main>
</body>
</html>
