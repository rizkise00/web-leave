<?php

namespace Tests\Feature;

use App\Models\Cuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCutiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(int $sisaCuti = 12): User
    {
        return User::create([
            'name'      => 'User Test',
            'email'     => 'user@test.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'sisa_cuti' => $sisaCuti,
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

    // ── Ajukan cuti ────────────────────────────────────────────────
    public function test_user_dapat_mengajukan_cuti_tahunan(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->addDay()->toDateString(),
            'alasan'          => 'Keperluan keluarga',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('cutis', [
            'user_id'    => $user->id,
            'jenis_cuti' => 'tahunan',
            'status'     => 'pending',
        ]);
    }

    public function test_user_dapat_mengajukan_cuti_sakit(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'sakit',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => 'Demam',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('cutis', ['jenis_cuti' => 'sakit', 'status' => 'pending']);
    }

    public function test_user_dapat_mengajukan_cuti_keperluan(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'keperluan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => 'Urusan administrasi',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('cutis', ['jenis_cuti' => 'keperluan']);
    }

    // ── Validasi pengajuan ─────────────────────────────────────────
    public function test_gagal_ajukan_cuti_tanpa_alasan(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => '',
        ])->assertSessionHasErrors('alasan');
    }

    public function test_gagal_ajukan_cuti_tanggal_mulai_lampau(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::yesterday()->toDateString(),
            'tanggal_selesai' => Carbon::today()->toDateString(),
            'alasan'          => 'Test',
        ])->assertSessionHasErrors('tanggal_mulai');
    }

    public function test_gagal_ajukan_cuti_jika_kuota_tidak_cukup(): void
    {
        $user = $this->makeUser(sisaCuti: 1);

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->addDays(4)->toDateString(),
            'alasan'          => 'Test kuota',
        ])->assertSessionHasErrors('jumlah_hari');
    }

    public function test_gagal_ajukan_cuti_tanggal_bertabrakan(): void
    {
        $user = $this->makeUser();
        $this->makeCuti($user);

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'sakit',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => 'Sakit',
        ])->assertSessionHasErrors('tanggal_mulai');
    }

    // ── Edit cuti ──────────────────────────────────────────────────
    public function test_user_dapat_edit_cuti_pending(): void
    {
        $user = $this->makeUser();
        $cuti = $this->makeCuti($user);

        $newMulai   = Carbon::now()->addDays(5)->toDateString();
        $newSelesai = Carbon::now()->addDays(6)->toDateString();

        $this->actingAs($user)->put("/cuti/{$cuti->id}", [
            'jenis_cuti'      => 'sakit',
            'tanggal_mulai'   => $newMulai,
            'tanggal_selesai' => $newSelesai,
            'alasan'          => 'Alasan baru',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('cutis', [
            'id'         => $cuti->id,
            'jenis_cuti' => 'sakit',
            'alasan'     => 'Alasan baru',
        ]);
    }

    public function test_user_tidak_dapat_edit_cuti_yang_sudah_disetujui(): void
    {
        $user = $this->makeUser();
        $cuti = $this->makeCuti($user, ['status' => 'disetujui']);

        $this->actingAs($user)->put("/cuti/{$cuti->id}", [
            'jenis_cuti'      => 'sakit',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => 'Test',
        ])->assertStatus(403);
    }

    public function test_user_tidak_dapat_edit_cuti_milik_orang_lain(): void
    {
        $user1 = $this->makeUser();
        $user2 = User::create([
            'name' => 'User Dua', 'email' => 'user2@test.com',
            'password' => Hash::make('password'), 'role' => 'user', 'sisa_cuti' => 12,
        ]);
        $cuti = $this->makeCuti($user2);

        $this->actingAs($user1)->put("/cuti/{$cuti->id}", [
            'jenis_cuti'      => 'sakit',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->toDateString(),
            'alasan'          => 'Test',
        ])->assertStatus(403);
    }

    // ── Batalkan cuti ──────────────────────────────────────────────
    public function test_user_dapat_batalkan_cuti_pending(): void
    {
        $user = $this->makeUser();
        $cuti = $this->makeCuti($user);

        $this->actingAs($user)->delete("/cuti/{$cuti->id}")
            ->assertRedirect('/dashboard');

        $this->assertDatabaseMissing('cutis', ['id' => $cuti->id]);
    }

    public function test_user_tidak_dapat_batalkan_cuti_yang_sudah_disetujui(): void
    {
        $user = $this->makeUser();
        $cuti = $this->makeCuti($user, ['status' => 'disetujui']);

        $this->actingAs($user)->delete("/cuti/{$cuti->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('cutis', ['id' => $cuti->id]);
    }

    public function test_user_tidak_dapat_batalkan_cuti_milik_orang_lain(): void
    {
        $user1 = $this->makeUser();
        $user2 = User::create([
            'name' => 'User Dua', 'email' => 'user2@test.com',
            'password' => Hash::make('password'), 'role' => 'user', 'sisa_cuti' => 12,
        ]);
        $cuti = $this->makeCuti($user2);

        $this->actingAs($user1)->delete("/cuti/{$cuti->id}")
            ->assertStatus(403);
    }

    // ── Jumlah hari ────────────────────────────────────────────────
    public function test_jumlah_hari_terhitung_dengan_benar(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/cuti/ajukan', [
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->addDays(2)->toDateString(),
            'alasan'          => 'Test',
        ]);

        $this->assertDatabaseHas('cutis', ['jumlah_hari' => 3]);
    }
}
