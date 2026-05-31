# Web Cuti — Sistem Manajemen Cuti Karyawan

Aplikasi berbasis web untuk pengelolaan pengajuan cuti karyawan dengan dua role: **User** dan **Manajer**.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13 + PHP 8.3 |
| Frontend | Tailwind CSS v4 + Vite 8 |
| Database | SQLite (dev/test) |
| Alert | SweetAlert2 |
| Export | PhpSpreadsheet |

---

## Fitur

### User
- Dashboard: statistik sisa cuti, cuti terpakai, pengajuan pending
- Ajukan cuti via modal popup (tahunan, sakit, keperluan)
- Edit & batalkan pengajuan yang masih pending
- Validasi kuota, tanggal lampau, dan tumpang tindih tanggal
- Profil lengkap: data akun, data pribadi (tanggal lahir, kota, alamat, tanggal bergabung), ubah password

### Manajer
- Dashboard: statistik pengajuan + daftar permohonan cuti pending (approve/reject langsung)
- Kelola Cuti: semua histori cuti seluruh karyawan
- Kelola User: tambah, edit, hapus user + reset kuota cuti
- Profil dengan data pribadi

---

## Instalasi

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js >= 18

### Langkah

```bash
# 1. Clone / masuk ke direktori
cd web-cuti

# 2. Install dependency PHP
composer install

# 3. Salin environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Jalankan migrasi + seeder
php artisan migrate --seed

# 6. Install dependency Node.js
npm install

# 7. Build assets
npm run build

# 8. Jalankan server
php artisan serve
```

Akses di: `http://localhost:8000`

---

## Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Manajer | `manajer@webcuti.com` | `password` |
| User | `user@webcuti.com` | `password` |

---

## Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| name | string | Nama lengkap |
| email | string | Email unik |
| password | string | Bcrypt |
| role | enum | `user` / `manajer` |
| sisa_cuti | tinyint | Default: 12 hari/tahun |

### Tabel `user_meta`
| Kolom | Tipe | Keterangan |
|---|---|---|
| user_id | FK | Relasi ke users |
| kota_kelahiran | string | Nullable |
| tanggal_lahir | date | Nullable |
| alamat | string | Nullable |
| tanggal_bergabung | date | Nullable |

### Tabel `cutis`
| Kolom | Tipe | Keterangan |
|---|---|---|
| user_id | FK | Pemohon |
| jenis_cuti | enum | `tahunan` / `sakit` / `keperluan` |
| tanggal_mulai | date | — |
| tanggal_selesai | date | — |
| jumlah_hari | tinyint | Dihitung otomatis |
| alasan | text | — |
| status | enum | `pending` / `disetujui` / `ditolak` |
| catatan_manajer | text | Nullable |
| disetujui_oleh | FK | Nullable, relasi ke users |
| disetujui_at | timestamp | Nullable |

---

## Routes Utama

```
GET  /login                     → Halaman login
POST /login                     → Proses login
POST /logout                    → Logout

GET  /dashboard                 → Dashboard (user/manajer)
GET  /profile                   → Profil saya
PUT  /profile                   → Update profil

# User
GET  /cuti                      → Riwayat cuti
GET  /cuti/ajukan               → Form ajukan cuti
POST /cuti/ajukan               → Simpan pengajuan
PUT  /cuti/{id}                 → Edit pengajuan (pending)
DEL  /cuti/{id}                 → Batalkan pengajuan (pending)

# Manajer
GET  /manajer/cuti              → Kelola cuti
POST /manajer/cuti/{id}/approve → Setujui cuti
POST /manajer/cuti/{id}/reject  → Tolak cuti

GET  /manajer/user              → Kelola user
POST /manajer/user              → Tambah user
PUT  /manajer/user/{id}         → Edit user
DEL  /manajer/user/{id}         → Hapus user
```

---

## Testing

```bash
php artisan test
```

**71 test cases — 138 assertions**

| Suite | File | Cases |
|---|---|---|
| Auth | `AuthTest.php` | 9 |
| Dashboard | `DashboardTest.php` | 7 |
| User Cuti | `UserCutiTest.php` | 12 |
| Manajer Cuti | `ManajerCutiTest.php` | 10 |
| Manajer User | `ManajerUserTest.php` | 12 |
| Profil | `ProfileTest.php` | 10 |

---

## Development

```bash
# Jalankan semua service sekaligus
composer dev

# Build assets untuk production
npm run build

# Hot reload saat development
npm run dev
```

---

## Aturan Bisnis

- Kuota cuti tahunan: **12 hari/tahun**
- Cuti sakit & keperluan **tidak** memotong kuota tahunan
- Pengajuan hanya bisa diedit/dibatalkan selama masih **pending**
- Tanggal cuti tidak boleh tumpang tindih dengan cuti yang sudah pending atau disetujui
