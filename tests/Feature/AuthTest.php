<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'user', string $status = 'approved'): User
    {
        return User::create([
            'name'           => $role === 'manajer' ? 'Manajer Test' : 'User Test',
            'email'          => $role . '@test.com',
            'password'       => Hash::make('password'),
            'role'           => $role,
            'sisa_cuti'      => $role === 'user' ? 12 : 0,
            'account_status' => $status,
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
        $this->makeUser('user', 'approved');

        $this->post('/login', ['email' => 'user@test.com', 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_manajer_dapat_login_dengan_kredensial_valid(): void
    {
        $this->makeUser('manajer', 'approved');

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

    // ── Blokir akun belum disetujui ────────────────────────────────
    public function test_user_pending_tidak_dapat_login(): void
    {
        $this->makeUser('user', 'pending');

        $this->post('/login', ['email' => 'user@test.com', 'password' => 'password'])
            ->assertRedirect('/login')
            ->assertSessionHas('account_status_error');

        $this->assertGuest();
    }

    public function test_user_ditolak_tidak_dapat_login(): void
    {
        $this->makeUser('user', 'rejected');

        $this->post('/login', ['email' => 'user@test.com', 'password' => 'password'])
            ->assertRedirect('/login')
            ->assertSessionHas('account_status_error');

        $this->assertGuest();
    }

    public function test_pesan_error_pending_sesuai(): void
    {
        $this->makeUser('user', 'pending');

        $response = $this->post('/login', ['email' => 'user@test.com', 'password' => 'password']);
        $response->assertSessionHas('account_status_error');

        $this->assertStringContainsString(
            'menunggu persetujuan',
            session('account_status_error')
        );
    }

    public function test_pesan_error_ditolak_sesuai(): void
    {
        $this->makeUser('user', 'rejected');

        $response = $this->post('/login', ['email' => 'user@test.com', 'password' => 'password']);
        $response->assertSessionHas('account_status_error');

        $this->assertStringContainsString(
            'ditolak',
            session('account_status_error')
        );
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

    // ── Halaman register ───────────────────────────────────────────
    public function test_halaman_register_dapat_diakses_tamu(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_halaman_register_tidak_dapat_diakses_user_login(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/register')->assertRedirect('/dashboard');
    }

    public function test_halaman_login_memiliki_link_daftar(): void
    {
        $this->get('/login')->assertSee('Daftar di sini');
    }

    public function test_halaman_register_memiliki_link_login(): void
    {
        $this->get('/register')->assertSee('Masuk di sini');
    }

    // ── Registrasi berhasil ────────────────────────────────────────
    public function test_registrasi_berhasil_membuat_user_baru(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'baru@test.com',
            'role'  => 'user',
        ]);
    }

    public function test_user_baru_memiliki_status_pending(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email'          => 'baru@test.com',
            'account_status' => 'pending',
        ]);
    }

    public function test_user_baru_mendapat_12_sisa_cuti(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email'     => 'baru@test.com',
            'sisa_cuti' => 12,
        ]);
    }

    public function test_registrasi_berhasil_menampilkan_pesan_sukses_di_login(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHas('register_success');
    }

    public function test_registrasi_tidak_langsung_login(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertGuest();
    }

    // ── Validasi registrasi ────────────────────────────────────────
    public function test_registrasi_gagal_tanpa_nama(): void
    {
        $this->post('/register', [
            'name'                  => '',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('name');
    }

    public function test_registrasi_gagal_tanpa_email(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => '',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_registrasi_gagal_dengan_email_tidak_valid(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'bukanemailvalid',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_registrasi_gagal_dengan_email_sudah_terdaftar(): void
    {
        $this->makeUser('user', 'approved');

        $this->post('/register', [
            'name'                  => 'User Lain',
            'email'                 => 'user@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_registrasi_gagal_password_terlalu_pendek(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => '123',
            'password_confirmation' => '123',
        ])->assertSessionHasErrors('password');
    }

    public function test_registrasi_gagal_konfirmasi_password_tidak_cocok(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'berbeda456',
        ])->assertSessionHasErrors('password');
    }

    public function test_registrasi_gagal_tanpa_password(): void
    {
        $this->post('/register', [
            'name'                  => 'Karyawan Baru',
            'email'                 => 'baru@test.com',
            'password'              => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors('password');
    }
}
