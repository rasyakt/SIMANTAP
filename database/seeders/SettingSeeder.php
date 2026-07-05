<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('app.nama_instansi', 'SIMANTAP - Sistem Informasi Manajemen Inventaris', 'general');
        Setting::setValue('app.logo', null, 'general');
        Setting::setValue('app.alamat', 'Jl. Pendidikan No. 1, Kota Contoh', 'general');
        Setting::setValue('app.kota', 'Kota Contoh', 'general');
        Setting::setValue('app.provinsi', 'Jawa Barat', 'general');
        Setting::setValue('app.nomor_telp', '(021) 12345678', 'general');
        Setting::setValue('app.email', 'info@simantap.test', 'general');
        Setting::setValue('app.website', 'https://simantap.test', 'general');
        Setting::setValue('notifikasi.ambang_stok_default', '5', 'notifikasi');
        Setting::setValue('notifikasi.hari_tenggang_perbaikan', '7', 'notifikasi');
        Setting::setValue('aset.format_kode', '{LOKASI}-{KATEGORI}-{NOMOR}', 'aset');
        Setting::setValue('aset.prefix_auto', 'true', 'aset');
    }
}
