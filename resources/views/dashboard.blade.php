<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Laravel Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=6">
</head>
<body>
    <main class="dashboard">
        <div class="dashboard-card">
            <span class="brand-mark">L<span>.</span></span>
            <span class="eyebrow">DASHBOARD AKUN</span>
            <h1>Halo, <em>{{ auth()->user()->name ?: 'selamat datang' }}!</em></h1>
            <p>Alamat email terverifikasi: <strong>{{ auth()->user()->email }}</strong>.</p>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="submit" type="submit"><span>Keluar dari akun</span><span class="arrow">↗</span></button></form>
        </div>
    </main>
</body>
</html>
