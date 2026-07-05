<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $lab = Location::create([
            'kode_lokasi' => 'LAB-TKJ-01',
            'nama' => 'Lab Komputer TKJ',
            'tipe_lokasi' => 'lab',
            'kapasitas' => 40,
            'deskripsi' => 'Laboratorium Komputer Jurusan Teknik Komputer dan Jaringan',
            'is_active' => true,
        ]);

        $gudang = Location::create([
            'kode_lokasi' => 'GDG-UTAMA-01',
            'nama' => 'Gudang Utama',
            'tipe_lokasi' => 'gudang',
            'kapasitas' => 1000,
            'deskripsi' => 'Gudang penyimpanan barang inventaris utama',
            'is_active' => true,
        ]);

        $kantor = Location::create([
            'kode_lokasi' => 'KTR-ADM-01',
            'nama' => 'Kantor Administrasi',
            'tipe_lokasi' => 'kantor',
            'kapasitas' => 20,
            'deskripsi' => 'Kantor bagian administrasi dan tata usaha',
            'is_active' => true,
        ]);

        $kelas = Location::create([
            'kode_lokasi' => 'KLS-XII-01',
            'nama' => 'Ruang Kelas XII RPL 1',
            'tipe_lokasi' => 'ruang_kelas',
            'kapasitas' => 36,
            'deskripsi' => 'Ruang kelas untuk jurusan Rekayasa Perangkat Lunak',
            'is_active' => true,
        ]);

        Location::create([
            'kode_lokasi' => 'LAB-MM-01',
            'nama' => 'Lab Multimedia',
            'tipe_lokasi' => 'lab',
            'kapasitas' => 30,
            'deskripsi' => 'Laboratorium Multimedia dan Desain Grafis',
            'is_active' => true,
        ]);
    }
}
