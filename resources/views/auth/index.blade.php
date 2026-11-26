<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $mode === 'login' ? 'Masuk' : 'Daftar' }} — Laravel Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=6">
    <script src="{{ asset('js/auth.js') }}?v=6" defer></script>
</head>
<body class="auth-page">
    <main class="auth-layout">
        <nav class="mobile-nav" aria-label="Pilih halaman">
            <button type="button" data-switch="login">Masuk</button>
            <button type="button" data-switch="register">Daftar</button>
        </nav>

        <section class="auth-card" id="auth-card" data-mode="{{ $mode }}" aria-label="Form masuk dan registrasi">
            <div class="form-pane login-pane" id="login-pane">
                <div class="form-content">
                    <span class="form-eyebrow">SELAMAT DATANG KEMBALI</span>
                    <h1>Masuk<span class="heading-dot">.</span></h1>
                    <p class="form-intro">Gunakan email dan kata sandi akunmu.</p>

                    @if ($mode === 'login' && session('success'))
                        <div class="notice success" role="status">{{ session('success') }}</div>
                    @endif
                    @if ($mode === 'login' && $errors->any())
                        <div class="notice error" role="alert">Periksa kembali email dan kata sandi.</div>
                    @endif

                    <form id="login-form" method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf
                        <div class="field @if ($mode === 'login' && $errors->has('email')) server-error @endif">
                            <label for="login-email">Email</label>
                            <input id="login-email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="nama@gmail.com" value="{{ $mode === 'login' ? old('email') : '' }}" required>
                            @if ($mode === 'login') @error('email') <small class="field-error">{{ $message }}</small> @enderror @endif
                        </div>
                        <div class="field @if ($mode === 'login' && $errors->has('password')) server-error @endif">
                            <label for="login-password">Kata sandi</label>
                            <div class="password-wrap">
                                <input id="login-password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required>
                                <button type="button" class="reveal" data-reveal="login-password" aria-label="Tampilkan kata sandi" aria-pressed="false">Lihat</button>
                            </div>
                            @if ($mode === 'login') @error('password') <small class="field-error">{{ $message }}</small> @enderror @endif
                        </div>
                        <div class="login-options">
                            <label class="remember"><input type="checkbox" name="remember" value="1" {{ $mode === 'login' && old('remember') ? 'checked' : '' }}> Ingat saya</label>
                            <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                        </div>
                        <button class="primary-button" type="submit">Masuk <span aria-hidden="true">→</span></button>
                    </form>
                    <p class="form-footer">Belum punya akun? <button type="button" data-switch="register">Daftar di sini</button></p>
                </div>
            </div>

            <div class="form-pane register-pane" id="register-pane">
                <div class="form-content">
                    <span class="form-eyebrow">MULAI PERJALANANMU</span>
                    <h1>Buat akun<span class="heading-dot">.</span></h1>
                    <p class="form-intro">Gunakan alamat Gmail aktif untuk membuat akun.</p>

                    @if ($mode === 'register' && $errors->any())
                        <div class="notice error" role="alert">Periksa kembali kolom yang ditandai.</div>
                    @endif

                    <form id="register-form" method="POST" action="{{ route('register.store') }}" novalidate>
                        @csrf
                        <div class="field @if ($mode === 'register' && $errors->has('name')) server-error @endif">
                            <label for="register-name">Nama</label>
                            <input id="register-name" name="name" type="text" autocomplete="name" placeholder="Nama lengkap" value="{{ $mode === 'register' ? old('name') : '' }}" maxlength="255" required>
                            @if ($mode === 'register') @error('name') <small class="field-error">{{ $message }}</small> @enderror @endif
                            <small class="live" id="register-name-live" aria-live="polite"></small>
                        </div>
                        <div class="field @if ($mode === 'register' && $errors->has('email')) server-error @endif">
                            <label for="register-email">Email Gmail</label>
                            <input id="register-email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="nama@gmail.com" value="{{ $mode === 'register' ? old('email') : '' }}" required>
                            @if ($mode === 'register') @error('email') <small class="field-error">{{ $message }}</small> @enderror @endif
                            <small class="live" id="register-email-live" aria-live="polite"></small>
                        </div>
                        <div class="field @if ($mode === 'register' && $errors->has('password')) server-error @endif">
                            <label for="register-password">Kata sandi</label>
                            <div class="password-wrap">
                                <input id="register-password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8" required>
                                <button type="button" class="reveal" data-reveal="register-password" aria-label="Tampilkan kata sandi" aria-pressed="false">Lihat</button>
                            </div>
                            @if ($mode === 'register') @error('password') <small class="field-error">{{ $message }}</small> @enderror @endif
                            <div class="strength" id="strength" data-score="0" aria-label="Kekuatan kata sandi"><span class="bars"><i></i><i></i><i></i><i></i></span><small id="strength-label">Belum diisi</small></div>
                            <small class="live" id="register-password-live" aria-live="polite"></small>
                        </div>
                        <div class="field @if ($mode === 'register' && $errors->has('password_confirmation')) server-error @endif">
                            <label for="register-confirmation">Konfirmasi kata sandi</label>
                            <div class="password-wrap">
                                <input id="register-confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi kata sandi" required>
                                <button type="button" class="reveal" data-reveal="register-confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">Lihat</button>
                            </div>
                            @if ($mode === 'register') @error('password_confirmation') <small class="field-error">{{ $message }}</small> @enderror @endif
                            <small class="live" id="register-confirmation-live" aria-live="polite"></small>
                        </div>
                        <button class="primary-button" type="submit">Daftar <span aria-hidden="true">→</span></button>
                    </form>
                    <p class="form-footer">Sudah punya akun? <button type="button" data-switch="login">Masuk di sini</button></p>
                </div>
            </div>

            <div class="overlay-viewport" aria-label="Pindah antara login dan registrasi">
                <div class="overlay-track">
                    <div class="overlay-panel overlay-left">
                        <span class="overlay-kicker">SELAMAT DATANG LAGI</span>
                        <h2>Halo,<br>teman!</h2>
                        <p>Sudah punya akun? Masuk dan lanjutkan perjalananmu bersama kami.</p>
                        <button type="button" class="outline-button" data-switch="login">Masuk <span aria-hidden="true">←</span></button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <span class="overlay-kicker">PETUALANGAN DIMULAI</span>
                        <h2>Mulai<br>sekarang.</h2>
                        <p>Belum punya akun? Bergabung dan temukan sesuatu yang baru.</p>
                        <button type="button" class="outline-button" data-switch="register">Daftar <span aria-hidden="true">→</span></button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
