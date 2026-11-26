<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa kata sandi — Laravel Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=6">
</head>
<body class="auth-page">
    <main class="account-card">
        <div class="account-content">
            <div class="form-content">
                <span class="form-eyebrow">PULIHKAN AKUN</span>
                <h1>Lupa kata sandi<span class="heading-dot">.</span></h1>
                <p class="form-intro">Masukkan email akunmu. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>
                @if (session('success'))
                    <div class="notice success" role="status">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="field @error('email') server-error @enderror">
                        <label for="email">Email akun</label>
                        <input id="email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="nama@gmail.com" value="{{ old('email') }}" required>
                        @error('email') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <button class="primary-button" type="submit">Kirim tautan pemulihan <span aria-hidden="true">→</span></button>
                </form>
                <div class="account-links"><a href="{{ route('login') }}">Kembali ke login</a></div>
            </div>
        </div>
        <div class="account-art" aria-hidden="true"></div>
    </main>
</body>
</html>
