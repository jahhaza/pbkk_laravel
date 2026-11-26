<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_login_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk')->assertSee('Ingat saya');
    }

    public function test_pengguna_dapat_masuk_dan_keluar(): void
    {
        $user = User::factory()->create(['email' => 'contoh@example.com']);

        $this->post('/login', [
            'email' => '  CONTOH@EXAMPLE.COM  ',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get('/dashboard')->assertOk()->assertSee('contoh@example.com');
        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_kata_sandi_salah_ditolak(): void
    {
        User::factory()->create(['email' => 'contoh@example.com']);

        $this->from('/login')->post('/login', [
            'email' => 'contoh@example.com',
            'password' => 'salah',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_memerlukan_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}
