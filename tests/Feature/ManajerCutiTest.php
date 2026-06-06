<?php

namespace Tests\Feature;

use App\Models\Cuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManajerCutiTest extends TestCase
{
    use RefreshDatabase;

    private function makeManajer(): User
    {
        return User::create([
            'name'      => 'Manajer Test',
            'email'     => 'manajer@test.com',
            'password'  => Hash::make('password'),
            'role'      => 'manajer',
            'sisa_cuti' => 0,
        ]);
    }

    private function makeUser(): User
    {
        return User::create([
            'name'      => 'User Test',
            'email'     => 'user@test.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'sisa_cuti' => 12,
        ]);
    }

    private function makeCuti(User $user, array $overrides = []): Cuti
    {
        return Cuti::create(array_merge([
            'user_id'         => $user->id,
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->addDay()->toDateString(),
            'jumlah_hari'     => 2,
            'alasan'          => 'Keperluan keluarga',
            'status'          => 'pending',
        ], $overrides));
    }

    // ── Halaman kelola cuti ────────────────────────────────────────
    public function test_manajer_dapat_akses_halaman_kelola_cuti(): void
    {
        $manajer = $this->makeManajer();
        $this->actingAs($manajer)->get('/manajer/cuti')->assertStatus(200);
    }

    public function test_halaman_kelola_cuti_menampilkan_data_cuti(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $this->makeCuti($user);

        $this->actingAs($manajer)->get('/manajer/cuti')
            ->assertSee($user->name)
            ->assertSee('Cuti Tahunan');
    }

    // ── Approve ────────────────────────────────────────────────────
    public function test_manajer_dapat_approve_cuti_pending(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user);

        $this->actingAs($manajer)
            ->post("/manajer/cuti/{$cuti->id}/approve")
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', ['id' => $cuti->id, 'status' => 'disetujui']);
    }

    public function test_approve_cuti_tahunan_mengurangi_sisa_cuti_user(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user, ['jumlah_hari' => 2]);

        $this->actingAs($manajer)->post("/manajer/cuti/{$cuti->id}/approve");

        $this->assertEquals(10, $user->fresh()->sisa_cuti);
    }

    public function test_tidak_dapat_approve_cuti_yang_sudah_disetujui(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user, ['status' => 'disetujui']);

        $this->actingAs($manajer)
            ->post("/manajer/cuti/{$cuti->id}/approve")
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', ['id' => $cuti->id, 'status' => 'disetujui']);
    }

    // ── Reject ─────────────────────────────────────────────────────
    public function test_manajer_dapat_reject_cuti_pending(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user);

        $this->actingAs($manajer)
            ->post("/manajer/cuti/{$cuti->id}/reject", ['catatan_manajer' => 'Staf penuh'])
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', [
            'id'               => $cuti->id,
            'status'           => 'ditolak',
            'catatan_manajer'  => 'Staf penuh',
        ]);
    }

    public function test_reject_tanpa_catatan_tetap_berhasil(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user);

        $this->actingAs($manajer)
            ->post("/manajer/cuti/{$cuti->id}/reject", ['catatan_manajer' => ''])
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', ['id' => $cuti->id, 'status' => 'ditolak']);
    }

    public function test_tidak_dapat_reject_cuti_yang_sudah_diproses(): void
    {
        $manajer = $this->makeManajer();
        $user    = $this->makeUser();
        $cuti    = $this->makeCuti($user, ['status' => 'ditolak']);

        $this->actingAs($manajer)
            ->post("/manajer/cuti/{$cuti->id}/reject")
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', ['id' => $cuti->id, 'status' => 'ditolak']);
    }

    // ── Proteksi role ──────────────────────────────────────────────
    public function test_user_biasa_tidak_dapat_approve_cuti(): void
    {
        $user  = $this->makeUser();
        $cuti  = $this->makeCuti($user);

        $this->actingAs($user)
            ->post("/manajer/cuti/{$cuti->id}/approve")
            ->assertStatus(403);
    }

    public function test_user_biasa_tidak_dapat_reject_cuti(): void
    {
        $user  = $this->makeUser();
        $cuti  = $this->makeCuti($user);

        $this->actingAs($user)
            ->post("/manajer/cuti/{$cuti->id}/reject")
            ->assertStatus(403);
    }
}
