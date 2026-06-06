<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah semua data lama ke tahunan, lalu ubah enum
        DB::statement("UPDATE cutis SET jenis_cuti = 'tahunan' WHERE jenis_cuti IN ('sakit', 'keperluan')");
        DB::statement("ALTER TABLE cutis MODIFY COLUMN jenis_cuti ENUM('tahunan') NOT NULL DEFAULT 'tahunan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cutis MODIFY COLUMN jenis_cuti ENUM('tahunan','sakit','keperluan') NOT NULL DEFAULT 'tahunan'");
    }
};
