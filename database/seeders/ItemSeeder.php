<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $kode = sprintf('LAB01-PC-%04d', $i);
            Item::create([
                'kode_aset' => $kode,
                'nama' => "Komputer $i",
                'deskripsi' => "PC Desktop Standard Lab Komputer #$i",
                'kategori_id' => 1,
                'lokasi_id' => 1,
                'item_template_id' => 1,
                'nomor_seri' => "SN-DELL-$kode",
                'tanggal_pengadaan' => now()->subMonths(rand(1, 24)),
                'vendor' => 'PT Teknologi Maju',
                'sumber' => 'Pembelian',
                'harga' => 8500000,
                'kondisi' => ['Baik', 'Baik', 'Baik', 'Rusak Ringan', 'Baik'][rand(0, 4)],
                'status_penggunaan' => ['Digunakan', 'Digunakan', 'Digunakan', 'Idle'][rand(0, 3)],
                'jumlah' => 1,
                'satuan' => 'unit',
                'created_by' => 1,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $kode = sprintf('LAB01-MON-%04d', $i);
            Item::create([
                'kode_aset' => $kode,
                'nama' => "Monitor LG 21.5\" #$i",
                'deskripsi' => "Monitor LCD 21.5 inch Lab Komputer",
                'kategori_id' => 2,
                'lokasi_id' => 1,
                'item_template_id' => 3,
                'nomor_seri' => "SN-LG-$kode",
                'tanggal_pengadaan' => now()->subMonths(rand(1, 24)),
                'vendor' => 'PT LG Electronics',
                'sumber' => 'Pembelian',
                'harga' => 1800000,
                'kondisi' => 'Baik',
                'status_penggunaan' => 'Digunakan',
                'jumlah' => 1,
                'satuan' => 'unit',
                'created_by' => 1,
            ]);
        }

        Item::create([
            'kode_aset' => 'GDG01-SWT-0001',
            'nama' => 'Switch Cisco 24 Port',
            'deskripsi' => 'Switch jaringan 24 port untuk lab',
            'kategori_id' => 3,
            'lokasi_id' => 1,
            'item_template_id' => 4,
            'nomor_seri' => 'SN-CISCO-SW001',
            'tanggal_pengadaan' => now()->subYear(),
            'vendor' => 'PT Cisco Indonesia',
            'sumber' => 'Pembelian',
            'harga' => 3500000,
            'kondisi' => 'Baik',
            'status_penggunaan' => 'Digunakan',
            'jumlah' => 1,
            'satuan' => 'unit',
            'created_by' => 1,
        ]);

        Item::create([
            'kode_aset' => 'GDG01-FRN-0001',
            'nama' => 'Meja Komputer Olympic MK-01',
            'deskripsi' => 'Meja komputer dengan rak keyboard',
            'kategori_id' => 6,
            'lokasi_id' => 2,
            'item_template_id' => 6,
            'nomor_seri' => null,
            'tanggal_pengadaan' => now()->subMonths(6),
            'vendor' => 'PT Olympic Furniture',
            'sumber' => 'Pembelian',
            'harga' => 750000,
            'kondisi' => 'Baik',
            'status_penggunaan' => 'Idle',
            'jumlah' => 5,
            'satuan' => 'unit',
            'created_by' => 1,
        ]);

        Item::create([
            'kode_aset' => 'KTR01-ELEK-0001',
            'nama' => 'AC Split 1 PK',
            'deskripsi' => 'Pendingin ruangan untuk kantor administrasi',
            'kategori_id' => 5,
            'lokasi_id' => 3,
            'item_template_id' => null,
            'nomor_seri' => 'SN-AC-KTR001',
            'tanggal_pengadaan' => now()->subMonths(8),
            'vendor' => 'PT Daikin Indonesia',
            'sumber' => 'Pembelian',
            'harga' => 4500000,
            'kondisi' => 'Rusak Ringan',
            'status_penggunaan' => 'Dalam Perbaikan',
            'jumlah' => 1,
            'satuan' => 'unit',
            'created_by' => 1,
        ]);

        Item::create([
            'kode_aset' => 'KLS01-TBL-0001',
            'nama' => 'Kursi Kantor Olympic KC-01',
            'deskripsi' => 'Kursi kantor untuk ruang kelas',
            'kategori_id' => 6,
            'lokasi_id' => 4,
            'item_template_id' => 7,
            'nomor_seri' => null,
            'tanggal_pengadaan' => now()->subMonths(3),
            'vendor' => 'PT Olympic Furniture',
            'sumber' => 'Donasi',
            'harga' => 550000,
            'kondisi' => 'Baik',
            'status_penggunaan' => 'Digunakan',
            'jumlah' => 20,
            'satuan' => 'unit',
            'created_by' => 1,
        ]);
    }
}
