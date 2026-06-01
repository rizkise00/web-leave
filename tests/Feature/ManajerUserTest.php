<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManajerUserTest extends TestCase
{
    use RefreshDatabase;

    private function makeManajer(): User
    {
        return User::create([
            'name'           => 'Manajer Test',
            'email'          => 'manajer@test.com',
            'password'       => Hash::make('password'),
            'role'           => 'manajer',
            'sisa_cuti'      => 0,
            'account_status' => 'approved',
        ]);
    }

    private function makeUser(array $overrides = []): User
    {
        static $seq = 0;
        $seq++;

        return User::create(array_merge([
            'name'           => "User Test {$seq}",
            'email'          => "user{$seq}@test.com",
            'password'       => Hash::make('password'),
            'role'           => 'user',
            'sisa_cuti'      => 12,
            'account_status' => 'approved',
        ], $overrides));
    }

    // ── Index ──────────────────────────────────────────────────────
    public function test_manajer_dapat_akses_halaman_kelola_user(): void
    {
        $manajer = $this->makeManajer();
        $this->actingAs($manajer)->get('/manajer/user')->assertStatus(200);
    }

    public function test_halaman_kelola_user_menampilkan_daftar_user(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();

        $this->actingAs($manajer)->get('/manajer/user')
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    public function test_halaman_kelola_user_menampilkan_badge_status_akun(): void
    {
        $manajer = $this->makeManajer();
        $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->actingAs($manajer)->get('/manajer/user')
            ->assertSee('Menunggu');
    }

    public function test_halaman_kelola_user_menampilkan_banner_jika_ada_pending(): void
    {
        $manajer = $this->makeManajer();
        $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->actingAs($manajer)->get('/manajer/user')
            ->assertSee('menunggu persetujuan');
    }

    // ── Create ─────────────────────────────────────────────────────
    public function test_manajer_dapat_tambah_user_baru(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)->post('/manajer/user', [
            'name'      => 'Karyawan Baru',
            'email'     => 'karyawan@test.com',
            'role'      => 'user',
            'sisa_cuti' => 12,
            'password'  => 'password123',
        ])->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', [
            'email' => 'karyawan@test.com',
            'role'  => 'user',
        ]);
    }

    public function test_manajer_dapat_tambah_manajer_baru(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)->post('/manajer/user', [
            'name'      => 'Manajer Baru',
            'email'     => 'manajer2@test.com',
            'role'      => 'manajer',
            'sisa_cuti' => 0,
            'password'  => 'password123',
        ])->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', ['email' => 'manajer2@test.com', 'role' => 'manajer']);
    }

    public function test_gagal_tambah_user_dengan_email_duplikat(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();

        $this->actingAs($manajer)->post('/manajer/user', [
            'name'      => 'Duplikat',
            'email'     => $user->email,
            'role'      => 'user',
            'sisa_cuti' => 12,
            'password'  => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_gagal_tambah_user_tanpa_password(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)->post('/manajer/user', [
            'name'      => 'Tanpa Password',
            'email'     => 'tanpa@test.com',
            'role'      => 'user',
            'sisa_cuti' => 12,
            'password'  => '',
        ])->assertSessionHasErrors('password');
    }

    // ── Update ─────────────────────────────────────────────────────
    public function test_manajer_dapat_update_data_user(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();

        $this->actingAs($manajer)->put("/manajer/user/{$user->id}", [
            'name'      => 'User Diupdate',
            'email'     => 'updated@test.com',
            'role'      => 'user',
            'sisa_cuti' => 8,
            'password'  => '',
        ])->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'name'      => 'User Diupdate',
            'email'     => 'updated@test.com',
            'sisa_cuti' => 8,
        ]);
    }

    public function test_update_password_user_berhasil(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();

        $this->actingAs($manajer)->put("/manajer/user/{$user->id}", [
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => 'user',
            'sisa_cuti' => 12,
            'password'  => 'newpassword',
        ])->assertRedirect('/manajer/user');

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_update_tanpa_password_tidak_mengubah_password(): void
    {
        $manajer     = $this->makeManajer();
        $user        = $this->makeUser();
        $oldPassword = $user->password;

        $this->actingAs($manajer)->put("/manajer/user/{$user->id}", [
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => 'user',
            'sisa_cuti' => 12,
            'password'  => '',
        ]);

        $this->assertEquals($oldPassword, $user->fresh()->password);
    }

    // ── Delete ─────────────────────────────────────────────────────
    public function test_manajer_dapat_hapus_user(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();

        $this->actingAs($manajer)->delete("/manajer/user/{$user->id}")
            ->assertRedirect('/manajer/user');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_manajer_tidak_dapat_hapus_dirinya_sendiri(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)->delete("/manajer/user/{$manajer->id}")
            ->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', ['id' => $manajer->id]);
    }

    // ── Approve ────────────────────────────────────────────────────
    public function test_manajer_dapat_approve_akun_pending(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending']);

        $this->actingAs($manajer)
            ->post("/manajer/user/{$user->id}/approve")
            ->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'account_status' => 'approved',
        ]);
    }

    public function test_approve_menampilkan_pesan_sukses(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending']);

        $this->actingAs($manajer)
            ->post("/manajer/user/{$user->id}/approve")
            ->assertSessionHas('success');
    }

    public function test_manajer_tidak_dapat_approve_dirinya_sendiri(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)
            ->post("/manajer/user/{$manajer->id}/approve")
            ->assertRedirect('/manajer/user')
            ->assertSessionHas('error');

        // Status manajer tidak berubah (tetap approved)
        $this->assertDatabaseHas('users', [
            'id'             => $manajer->id,
            'account_status' => 'approved',
        ]);
    }

    public function test_user_yang_diapprove_dapat_login(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        // Approve via manajer, kemudian logout
        $this->actingAs($manajer)->post("/manajer/user/{$user->id}/approve");
        $this->post('/logout');
        $this->assertGuest();

        // User sekarang bisa login
        $this->post('/login', ['email' => 'pending@test.com', 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    // ── Reject ─────────────────────────────────────────────────────
    public function test_manajer_dapat_reject_akun_pending(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending']);

        $this->actingAs($manajer)
            ->post("/manajer/user/{$user->id}/reject")
            ->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'account_status' => 'rejected',
        ]);
    }

    public function test_reject_menampilkan_pesan_sukses(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending']);

        $this->actingAs($manajer)
            ->post("/manajer/user/{$user->id}/reject")
            ->assertSessionHas('success');
    }

    public function test_manajer_tidak_dapat_reject_dirinya_sendiri(): void
    {
        $manajer = $this->makeManajer();

        $this->actingAs($manajer)
            ->post("/manajer/user/{$manajer->id}/reject")
            ->assertRedirect('/manajer/user')
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('users', [
            'id'             => $manajer->id,
            'account_status' => 'rejected',
        ]);
    }

    public function test_user_yang_direject_tidak_dapat_login(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        // Reject via manajer, kemudian logout
        $this->actingAs($manajer)->post("/manajer/user/{$user->id}/reject");
        $this->post('/logout');
        $this->assertGuest();

        // User tidak bisa login
        $this->post('/login', ['email' => 'pending@test.com', 'password' => 'password'])
            ->assertRedirect('/login')
            ->assertSessionHas('account_status_error');

        $this->assertGuest();
    }

    public function test_approve_akun_yang_sudah_approved_tetap_approved(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser(['account_status' => 'approved']);

        $this->actingAs($manajer)
            ->post("/manajer/user/{$user->id}/approve")
            ->assertRedirect('/manajer/user');

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'account_status' => 'approved',
        ]);
    }

    // ── Proteksi ───────────────────────────────────────────────────
    public function test_user_biasa_tidak_dapat_akses_kelola_user(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/manajer/user')->assertStatus(403);
    }

    public function test_user_biasa_tidak_dapat_hapus_user_lain(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser(['email' => 'user2@test.com']);

        $this->actingAs($user1)->delete("/manajer/user/{$user2->id}")
            ->assertStatus(403);
    }

    public function test_user_biasa_tidak_dapat_approve_user(): void
    {
        $user    = $this->makeUser();
        $pending = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->actingAs($user)
            ->post("/manajer/user/{$pending->id}/approve")
            ->assertStatus(403);
    }

    public function test_user_biasa_tidak_dapat_reject_user(): void
    {
        $user    = $this->makeUser();
        $pending = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->actingAs($user)
            ->post("/manajer/user/{$pending->id}/reject")
            ->assertStatus(403);
    }

    public function test_tamu_tidak_dapat_approve_user(): void
    {
        $user = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->post("/manajer/user/{$user->id}/approve")
            ->assertRedirect('/login');
    }

    public function test_tamu_tidak_dapat_reject_user(): void
    {
        $user = $this->makeUser(['account_status' => 'pending', 'email' => 'pending@test.com']);

        $this->post("/manajer/user/{$user->id}/reject")
            ->assertRedirect('/login');
    }
}
