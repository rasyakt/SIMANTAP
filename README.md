# SIMANTAP - Sistem Informasi Manajemen Inventaris Barang

Aplikasi manajemen inventaris barang berbasis web yang fleksibel untuk multi-lokasi (lab, gudang, kantor, ruang kelas, dll). Dibangun dengan Laravel 13, Livewire 3, Tailwind CSS, dan MySQL.

## Fitur Utama

- Manajemen Lokasi (multi-tipe: lab, gudang, kantor, ruang_kelas)
- Manajemen Kategori & Sub-kategori
- Manajemen Template Barang
- Manajemen Barang/Aset dengan QR Code
- Manajemen Stok Gudang & Sparepart
- Riwayat Perbaikan & Maintenance
- Riwayat Mutasi Barang
- Import Data dari Excel
- Export Laporan (PDF & Excel)
- Dashboard dengan Chart.js
- Role & Permission (Super Admin, Admin, Staff, Pimpinan)
- Notifikasi Stok Menipis
- Activity Log (Audit Trail)
- Dark Mode

## Teknologi

| Package | Versi |
|---------|-------|
| Laravel | ^13.8 |
| Livewire | ^3.8 |
| Tailwind CSS | ^3.1 |
| Alpine.js | ^3.15 |
| Chart.js | ^4.5 |
| Spatie Permission | ^8.3 |
| Spatie Activitylog | ^5.0 |
| Laravel Excel | ^3.1 |
| Laravel DomPDF | ^3.1 |
| Simple QR Code | ^4.2 |
| Laravel Breeze | ^2.4 (Livewire Stack) |

## Persyaratan Sistem

- PHP ^8.3
- Composer 2.x
- MySQL 8.0+
- Node.js 18+
- NPM 9+

## Instalasi di Laragon

1. Clone repositori:
```bash
git clone <repository-url> simantap
cd simantap
```

2. Install dependensi PHP:
```bash
composer install
```

3. Copy file `.env` dan sesuaikan konfigurasi database:
```bash
copy .env.example .env
```
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simantap
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate key:
```bash
php artisan key:generate
```

5. Buat database `simantap` di MySQL.

6. Jalankan migrasi dan seeder:
```bash
php artisan migrate --seed
```

7. Install dependensi NPM:
```bash
npm install
```

8. Build frontend assets:
```bash
npm run build
```

9. Buat symlink storage:
```bash
php artisan storage:link
```

10. Jalankan development server:
```bash
php artisan serve
```

Atau gunakan mode development (dengan Vite hot reload):
```bash
npm run dev
```

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@simantap.test | password |
| Admin | admin@simantap.test | password |
| Staff | staff@simantap.test | password |
| Pimpinan | pimpinan@simantap.test | password |

## Struktur Modul

```
SIMANTAP/
├── app/
│   ├── Livewire/
│   │   ├── Dashboard/       # Dashboard & chart
│   │   ├── Lokasi/          # Manajemen lokasi
│   │   ├── Kategori/        # Manajemen kategori
│   │   ├── Template/        # Template barang
│   │   ├── Barang/          # Barang/aset (CRUD, QR, tandai rusak)
│   │   ├── Stok/            # Stok gudang & mutasi
│   │   ├── Perbaikan/       # Riwayat perbaikan
│   │   ├── Laporan/         # Laporan & export
│   │   ├── Pengguna/        # Manajemen user
│   │   └── Pengaturan/      # Pengaturan aplikasi
│   ├── Exports/             # Export PDF & Excel
│   └── Imports/             # Import Excel
├── database/
│   └── migrations/          # Semua migrasi database
│   └── seeders/             # Data dummy & role/permission
└── resources/
    └── views/
        └── livewire/        # Blade views per modul
```
