<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'user'): User
    {
        return User::create([
            'name'      => $role === 'manajer' ? 'Manajer Test' : 'User Test',
            'email'     => $role . '@test.com',
            'password'  => Hash::make('password'),
            'role'      => $role,
            'sisa_cuti' => 12,
        ]);
    }

    // ── Halaman login ──────────────────────────────────────────────
    public function test_halaman_login_dapat_diakses_tamu(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_login_redirect_ke_dashboard_jika_sudah_login(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
    }

    // ── Login berhasil ─────────────────────────────────────────────
    public function test_user_dapat_login_dengan_kredensial_valid(): void
    {
        $this->makeUser('user');

        $this->post('/login', ['email' => 'user@test.com', 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_manajer_dapat_login_dengan_kredensial_valid(): void
    {
        $this->makeUser('manajer');

        $this->post('/login', ['email' => 'manajer@test.com', 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    // ── Login gagal ────────────────────────────────────────────────
    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->makeUser();

        $this->post('/login', ['email' => 'user@test.com', 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_gagal_dengan_email_tidak_terdaftar(): void
    {
        $this->post('/login', ['email' => 'tidakada@test.com', 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_gagal_tanpa_email(): void
    {
        $this->post('/login', ['email' => '', 'password' => 'password'])
            ->assertSessionHasErrors('email');
    }

    public function test_login_gagal_tanpa_password(): void
    {
        $this->makeUser();
        $this->post('/login', ['email' => 'user@test.com', 'password' => ''])
            ->assertSessionHasErrors('password');
    }

    // ── Logout ─────────────────────────────────────────────────────
    public function test_user_dapat_logout(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    // ── Proteksi route ─────────────────────────────────────────────
    public function test_tamu_diredirect_ke_login_saat_akses_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_tamu_diredirect_ke_login_saat_akses_profile(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }
}
