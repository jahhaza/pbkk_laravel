<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulir_registrasi_dapat_dibuka(): void
    {
        $this->get('/register')->assertOk()->assertSee('Buat akun')->assertSee('nama@gmail.com');
    }

    public function test_pengguna_baru_tersimpan_dengan_kata_sandi_terenkripsi(): void
    {
        Notification::fake();
        $this->post('/register', [
            'name' => 'Pengguna Baru',
            'email' => '  PENGGUNA@GMAIL.COM  ',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('verification.notice'));

        $user = User::query()->sole();
        $this->assertSame('Pengguna Baru', $user->name);
        $this->assertSame('pengguna@gmail.com', $user->email);
        $this->assertNotSame('rahasia123', $user->password);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->get('/dashboard')->assertRedirect(route('verification.notice'));
    }

    public function test_email_tidak_valid_dan_kata_sandi_tidak_cocok_ditolak(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Contoh Pengguna',
            'email' => 'bukan-email',
            'password' => '123',
            'password_confirmation' => 'beda',
        ])->assertRedirect('/register')->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_email_yang_sudah_terdaftar_ditolak(): void
    {
        User::factory()->create(['email' => 'contoh@gmail.com']);

        $this->post('/register', [
            'name' => 'Contoh Pengguna',
            'email' => 'CONTOH@GMAIL.COM',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_selain_gmail_ditolak(): void
    {
        $this->post('/register', [
            'name' => 'Pengguna',
            'email' => 'pengguna@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_kolom_wajib_tidak_boleh_kosong(): void
    {
        $this->post('/register', [])->assertSessionHasErrors(['name', 'email', 'password', 'password_confirmation']);
        $this->assertDatabaseCount('users', 0);
    }
}
