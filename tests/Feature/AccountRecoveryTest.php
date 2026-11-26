<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_meminta_tautan_pemulihan_dan_mengganti_kata_sandi(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'pengguna@gmail.com']);

        $this->post('/forgot-password', ['email' => ' PENGGUNA@GMAIL.COM '])
            ->assertRedirect()
            ->assertSessionHas('success');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
            $token = $notification->token;
            return true;
        });

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ])->assertRedirect(route('login'))->assertSessionHas('success');

        $this->assertTrue(Hash::check('passwordBaru123', $user->fresh()->password));
    }

    public function test_email_tidak_terdaftar_tidak_dibocorkan(): void
    {
        $this->post('/forgot-password', ['email' => 'tidakada@gmail.com'])
            ->assertRedirect()
            ->assertSessionHas('success');
    }
}
