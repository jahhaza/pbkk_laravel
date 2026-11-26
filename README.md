# Laravel Registrasi — Double Slider Login & Register

Aplikasi registrasi dan login menggunakan **Laravel 13**, PHP, Blade, SQLite, CSS, dan JavaScript. Tampilan autentikasi memakai animasi *double slider*. Validasi, penyimpanan akun, verifikasi email, pemulihan kata sandi, sesi login, dan proteksi dashboard dikerjakan oleh Laravel.

## Daftar Isi

- [Bukti Penggunaan Laravel](#bukti-penggunaan-laravel)
- [Fitur](#fitur)
- [Persyaratan](#persyaratan)
- [Menjalankan di Windows](#menjalankan-di-windows)
- [Menjalankan di macOS atau Linux](#menjalankan-di-macos-atau-linux)
- [Konfigurasi Email dan Server](#konfigurasi-email-dan-server)
- [Struktur Proyek](#struktur-proyek)
- [Pengujian](#pengujian)
- [About Laravel](#about-laravel)
- [Learning Laravel](#learning-laravel)
- [Agentic Development](#agentic-development)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [Security Vulnerabilities](#security-vulnerabilities)
- [License](#license)

## Bukti Penggunaan Laravel

Proyek ini adalah aplikasi Laravel:

| Bukti | Lokasi |
| --- | --- |
| Dependensi `laravel/framework: ^13.17` | [`composer.json`](composer.json) |
| Versi dependensi yang dikunci | [`composer.lock`](composer.lock) |
| Bootstrap aplikasi Laravel | [`bootstrap/app.php`](bootstrap/app.php) |
| Perintah Artisan | [`artisan`](artisan) |
| Route autentikasi dan dashboard | [`routes/web.php`](routes/web.php) |
| Controller dan validasi request | [`app/Http`](app/Http) |
| Model Eloquent dan migrasi database | [`app/Models/User.php`](app/Models/User.php), [`database/migrations`](database/migrations) |
| Tampilan Blade | [`resources/views/auth/index.blade.php`](resources/views/auth/index.blade.php) |

Untuk melihat versi Laravel yang terpasang setelah `composer install`, jalankan `php artisan --version`.

## Fitur

- Registrasi dengan nama, email, kata sandi, dan konfirmasi kata sandi.
- Pendaftaran hanya menerima alamat `@gmail.com` yang valid dan belum terpakai. Akun yang sudah ada tetap bisa login dengan alamat sebelumnya.
- Validasi Laravel: nama wajib; kata sandi minimal 8 karakter dan harus dikonfirmasi.
- Normalisasi email, hash kata sandi, dan pesan kesalahan pada form.
- Verifikasi alamat email sebelum membuka dashboard, kirim ulang tautan verifikasi, dan reset kata sandi lewat email.
- Login berbasis sesi, opsi “Ingat saya”, batas percobaan pada route autentikasi, dashboard terlindungi, dan logout.
- Double slider interaktif dengan GIF laut, URL `/register` dan `/login` yang mengikuti mode, serta tampilan responsif.
- Indikator kekuatan kata sandi, validasi langsung, dan tombol lihat/sembunyikan kata sandi.
- SQLite sebagai database bawaan. Tampilan memakai CSS dan JavaScript langsung tanpa langkah build frontend.

## Persyaratan

- PHP **8.3 atau lebih baru**, beserta ekstensi Laravel yang diperlukan termasuk `mbstring` dan `pdo_sqlite`.
- Composer 2.
- Git jika ingin melakukan `git clone` atau `git push`.

## Menjalankan di Windows

Jalankan dari **CMD** di folder proyek. Jika memakai Laravel Herd dan `php` atau `composer` belum dikenali pada CMD baru:

```bat
set "PATH=%USERPROFILE%\.config\herd\bin;%PATH%"
php -v
composer -V
```

Siapkan dan jalankan aplikasi:

```bat
composer install
if not exist .env copy .env.example .env
php artisan key:generate
if not exist database\database.sqlite type nul > database\database.sqlite
php artisan migrate
php artisan serve
```

Buka [http://127.0.0.1:8000/register](http://127.0.0.1:8000/register) atau [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login). Biarkan jendela `php artisan serve` tetap terbuka.

> Perintah di atas untuk **CMD**. Di PowerShell, gunakan `Copy-Item .env.example .env` untuk menyalin file lingkungan dan `New-Item -ItemType File database/database.sqlite` untuk membuat database bila belum ada. Jangan menimpa `.env` atau database yang sudah berisi data.

## Menjalankan di macOS atau Linux

```bash
composer install
test -f .env || cp .env.example .env
php artisan key:generate
test -f database/database.sqlite || touch database/database.sqlite
php artisan migrate
php artisan serve
```

Jika memperbarui instalasi lama, jalankan `php artisan migrate` lagi untuk menambahkan kolom nama. Migrasi ini mempertahankan akun yang sudah ada.

## Konfigurasi Email dan Server

Pendaftaran dan login sudah bekerja secara lokal. **Email sungguhan baru terkirim setelah mailer dikonfigurasi.** Pengaturan awal `MAIL_MAILER=log` menyimpan tautan verifikasi dan reset di `storage/logs/laravel.log`; ini berguna untuk uji lokal, tetapi tidak mengirim ke inbox.

Untuk memakai aplikasi pada server publik:

1. Siapkan domain, HTTPS, database persisten, dan layanan pengiriman email SMTP atau transport lain yang didukung Laravel.
2. Isi `.env` di server dengan `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda`, konfigurasi `DB_*`, serta `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, dan `MAIL_FROM_NAME` sesuai penyedia email. `APP_KEY` harus terisi dari `php artisan key:generate`.
3. Arahkan document root web server ke folder `public/`, lalu jalankan `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, dan `php artisan config:cache`.
4. Uji pendaftaran menggunakan Gmail milik sendiri, klik tautan verifikasi yang diterima, login, reset kata sandi, dan logout.

Jangan commit `.env`, kata sandi SMTP, atau database. Akun lama yang belum memiliki `email_verified_at` akan diminta memverifikasi email saat membuka dashboard. Tanpa layanan email yang aktif, alur verifikasi dan pemulihan kata sandi tidak bisa selesai melalui inbox.

## Struktur Proyek

```text
app/Http/Controllers/              Controller registrasi, login, logout, reset kata sandi
app/Http/Requests/                 Validasi form
app/Models/User.php                Model pengguna Eloquent
bootstrap/app.php                  Bootstrap Laravel
database/migrations/              Skema database
public/css/auth.css               Gaya halaman autentikasi
public/js/auth.js                 Animasi slider dan interaksi form
public/images/image.gif           GIF dari contoh desain
resources/views/auth/            Form login, registrasi, verifikasi, dan reset
resources/views/dashboard.blade.php   Dashboard setelah login
routes/web.php                    Route aplikasi
tests/Feature/                    Pengujian fitur
```

Untuk MySQL, ubah konfigurasi `DB_*` di `.env`, lalu jalankan `php artisan migrate`.

## Pengujian

```bash
php artisan test
```

Sebelum mengunggah ke GitHub, pastikan `.env`, `vendor/`, dan database lokal tidak ikut di-commit. Ketiganya sudah dikecualikan oleh `.gitignore`.

## About Laravel

[Laravel](https://laravel.com/) adalah framework aplikasi web PHP. Proyek ini menggunakan beberapa komponennya secara langsung:

- [Routing](https://laravel.com/docs/routing) untuk URL dan middleware.
- [Validation](https://laravel.com/docs/validation) untuk input registrasi dan login.
- [Authentication](https://laravel.com/docs/authentication) untuk sesi login dan dashboard terlindungi.
- [Eloquent ORM](https://laravel.com/docs/eloquent) untuk model pengguna.
- [Database migrations](https://laravel.com/docs/migrations) untuk skema database.
- [Blade templates](https://laravel.com/docs/blade) untuk tampilan form.

Laravel juga menyediakan [dependency injection](https://laravel.com/docs/container), [session](https://laravel.com/docs/session), [cache](https://laravel.com/docs/cache), [queues](https://laravel.com/docs/queues), dan [broadcasting](https://laravel.com/docs/broadcasting). Daftar terakhir ini adalah kemampuan framework, bukan klaim bahwa semuanya sudah dipakai oleh aplikasi.

## Learning Laravel

Pelajari Laravel melalui [dokumentasi resmi](https://laravel.com/docs), [Laravel Learn](https://laravel.com/learn), atau [Laracasts](https://laracasts.com/). Lihat juga [README resmi Laravel](https://github.com/laravel/laravel#readme).

## Agentic Development

[Laravel Boost](https://laravel.com/docs/ai) adalah alat resmi opsional untuk pengembangan dengan agen AI. Proyek ini tidak memerlukannya untuk berjalan. Jika ingin memakainya, ikuti petunjuk instalasi pada dokumentasi versi Laravel yang digunakan.

## Contributing

Kontribusi untuk **proyek ini** dapat diajukan melalui issue atau pull request pada repository tempat kode ini diunggah. Untuk kontribusi ke framework Laravel, lihat [panduan kontribusi Laravel](https://laravel.com/docs/contributions).

## Code of Conduct

Saat berkontribusi, ikuti [Code of Conduct komunitas Laravel](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Jika menemukan celah keamanan pada **aplikasi ini**, lihat [SECURITY.md](SECURITY.md) untuk cara melaporkannya. Untuk celah pada **framework Laravel**, ikuti [kebijakan keamanan Laravel](https://laravel.com/docs/security#reporting-security-vulnerabilities). Jangan mengirim kredensial atau detail kerentanan melalui issue publik.

## License

Kode aplikasi dalam repository ini memakai [lisensi MIT](LICENSE). Framework Laravel juga berlisensi [MIT](https://github.com/laravel/framework/blob/master/LICENSE.md). GIF `public/images/image.gif` berasal dari arsip contoh Hellocode yang diberikan pengguna dan tetap tunduk pada hak pembuat aset tersebut. Referensi desain: [video Double Slider Registration and Login Page](https://www.youtube.com/watch?v=cREkblx1Aus). Inspirasi form registrasi awal: [tutorial ID Laravel](https://id-laravel.com/post/membuat-form-registrasi-disertai-validasi/).
