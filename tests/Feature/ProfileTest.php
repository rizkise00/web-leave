<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'user'): User
    {
        return User::create([
            'name'      => ucfirst($role) . ' Test',
            'email'     => $role . '@test.com',
            'password'  => Hash::make('password'),
            'role'      => $role,
            'sisa_cuti' => 12,
        ]);
    }

    // ── View profile ───────────────────────────────────────────────
    public function test_user_dapat_akses_halaman_profil(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/profile')->assertStatus(200);
    }

    public function test_halaman_profil_menampilkan_data_user(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/profile')
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    public function test_tamu_tidak_dapat_akses_profil(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    // ── Update profil ──────────────────────────────────────────────
    public function test_user_dapat_update_nama_dan_email(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name'  => 'Nama Baru',
            'email' => 'baruemail@test.com',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Nama Baru',
            'email' => 'baruemail@test.com',
        ]);
    }

    public function test_gagal_update_email_duplikat(): void
    {
        $user1 = $this->makeUser('user');
        $user2 = User::create([
            'name' => 'User 2', 'email' => 'user2@test.com',
            'password' => Hash::make('password'), 'role' => 'user', 'sisa_cuti' => 12,
        ]);

        $this->actingAs($user1)->put('/profile', [
            'name'  => $user1->name,
            'email' => 'user2@test.com',
        ])->assertSessionHasErrors('email');
    }

    // ── Data meta ──────────────────────────────────────────────────
    public function test_user_dapat_simpan_data_pribadi(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name'               => $user->name,
            'email'              => $user->email,
            'kota_kelahiran'     => 'Bandung',
            'tanggal_lahir'      => '1995-06-15',
            'alamat'             => 'Jl. Merdeka No. 10',
            'tanggal_bergabung'  => '2020-01-01',
        ])->assertRedirect();

        $meta = \App\Models\UserMeta::where('user_id', $user->id)->first();
        $this->assertNotNull($meta);
        $this->assertEquals('Bandung', $meta->kota_kelahiran);
        $this->assertEquals('1995-06-15', $meta->tanggal_lahir->toDateString());
        $this->assertEquals('Jl. Merdeka No. 10', $meta->alamat);
        $this->assertEquals('2020-01-01', $meta->tanggal_bergabung->toDateString());
    }

    public function test_update_meta_dua_kali_tidak_duplikat(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name, 'email' => $user->email,
            'kota_kelahiran' => 'Jakarta',
        ]);

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name, 'email' => $user->email,
            'kota_kelahiran' => 'Surabaya',
        ]);

        $this->assertEquals(1, UserMeta::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('user_meta', ['kota_kelahiran' => 'Surabaya']);
    }

    // ── Ubah password ──────────────────────────────────────────────
    public function test_user_dapat_ubah_password_dengan_password_lama_benar(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name'                     => $user->name,
            'email'                    => $user->email,
            'current_password'         => 'password',
            'new_password'             => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_gagal_ubah_password_dengan_password_lama_salah(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name'                     => $user->name,
            'email'                    => $user->email,
            'current_password'         => 'salah',
            'new_password'             => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_gagal_ubah_password_jika_konfirmasi_tidak_cocok(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/profile', [
            'name'                     => $user->name,
            'email'                    => $user->email,
            'current_password'         => 'password',
            'new_password'             => 'newpassword123',
            'new_password_confirmation' => 'berbeda456',
        ])->assertSessionHasErrors('new_password');
    }

    public function test_tidak_ubah_password_jika_new_password_kosong(): void
    {
        $user        = $this->makeUser();
        $oldPassword = $user->password;

        $this->actingAs($user)->put('/profile', [
            'name'  => $user->name,
            'email' => $user->email,
        ]);

        $this->assertEquals($oldPassword, $user->fresh()->password);
    }

    // ── Manajer juga bisa akses profil ────────────────────────────
    public function test_manajer_dapat_akses_dan_update_profil(): void
    {
        $manajer = $this->makeUser('manajer');

        $this->actingAs($manajer)->get('/profile')->assertStatus(200);

        $this->actingAs($manajer)->put('/profile', [
            'name'  => 'Manajer Diupdate',
            'email' => $manajer->email,
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $manajer->id, 'name' => 'Manajer Diupdate']);
    }
}
