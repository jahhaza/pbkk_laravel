<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi email — Laravel Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=6">
</head>
<body class="auth-page">
    <main class="account-card">
        <div class="account-content">
            <div class="form-content">
                <span class="form-eyebrow">SATU LANGKAH LAGI</span>
                <h1>Verifikasi email<span class="heading-dot">.</span></h1>
                @if (auth()->user()->hasVerifiedEmail())
                    <p class="form-intro">Email <strong>{{ auth()->user()->email }}</strong> sudah terverifikasi.</p>
                    <a class="primary-button" href="{{ route('dashboard') }}">Buka dashboard <span aria-hidden="true">→</span></a>
                @else
                    <p class="form-intro">Kami mengirim tautan verifikasi ke <strong>{{ auth()->user()->email }}</strong>. Buka email tersebut dan klik tautannya sebelum melanjutkan.</p>
                    @if (session('success'))
                        <div class="notice success" role="status">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button class="primary-button" type="submit">Kirim ulang email <span aria-hidden="true">→</span></button>
                    </form>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-button" type="submit">Keluar dari akun</button>
                </form>
            </div>
        </div>
        <div class="account-art" aria-hidden="true"></div>
    </main>
</body>
</html>
