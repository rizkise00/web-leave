<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    // ── User dashboard ─────────────────────────────────────────────
    public function test_user_dapat_akses_dashboard(): void
    {
        $user = $this->makeUser('user');
        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    }

    public function test_dashboard_user_menampilkan_nama(): void
    {
        $user = $this->makeUser('user');
        $this->actingAs($user)->get('/dashboard')->assertSee($user->name);
    }

    public function test_dashboard_user_menampilkan_sisa_cuti(): void
    {
        $user = $this->makeUser('user');
        $this->actingAs($user)->get('/dashboard')->assertSee('Sisa Cuti');
    }

    // ── Manajer dashboard ──────────────────────────────────────────
    public function test_manajer_dapat_akses_dashboard(): void
    {
        $manajer = $this->makeUser('manajer');
        $this->actingAs($manajer)->get('/dashboard')->assertStatus(200);
    }

    public function test_dashboard_manajer_menampilkan_pengajuan_permohonan(): void
    {
        $manajer = $this->makeUser('manajer');
        $this->actingAs($manajer)->get('/dashboard')
            ->assertSee('Pengajuan Permohonan Cuti');
    }

    // ── Role isolation ─────────────────────────────────────────────
    public function test_user_tidak_dapat_akses_kelola_cuti_manajer(): void
    {
        $user = $this->makeUser('user');
        $this->actingAs($user)->get('/manajer/cuti')->assertStatus(403);
    }

    public function test_user_tidak_dapat_akses_kelola_user_manajer(): void
    {
        $user = $this->makeUser('user');
        $this->actingAs($user)->get('/manajer/user')->assertStatus(403);
    }

    public function test_manajer_tidak_dapat_akses_route_cuti_user(): void
    {
        $manajer = $this->makeUser('manajer');
        $this->actingAs($manajer)->get('/cuti')->assertStatus(403);
    }
}
