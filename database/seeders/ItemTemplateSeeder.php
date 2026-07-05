<?php

namespace Database\Seeders;

use App\Models\ItemTemplate;
use Illuminate\Database\Seeder;

class ItemTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ItemTemplate::create([
            'nama' => 'PC Desktop Standard',
            'merk' => 'Dell',
            'tipe_model' => 'Optiplex 3090',
            'satuan' => 'unit',
            'spesifikasi' => 'Intel Core i5, RAM 8GB, SSD 256GB',
            'kategori_id' => 1,
            'estimasi_harga' => 8500000,
            'has_serial_number' => true,
        ]);

        ItemTemplate::create([
            'nama' => 'Laptop Pendidikan',
            'merk' => 'Lenovo',
            'tipe_model' => 'ThinkPad E14',
            'satuan' => 'unit',
            'spesifikasi' => 'Intel Core i5, RAM 8GB, SSD 512GB',
            'kategori_id' => 1,
            'estimasi_harga' => 12000000,
            'has_serial_number' => true,
        ]);

        ItemTemplate::create([
            'nama' => 'Monitor LCD 21.5"',
            'merk' => 'LG',
            'tipe_model' => '22MK400H',
            'satuan' => 'unit',
            'spesifikasi' => '21.5 inch, HDMI, VGA',
            'kategori_id' => 2,
            'estimasi_harga' => 1800000,
            'has_serial_number' => true,
        ]);

        ItemTemplate::create([
            'nama' => 'Switch 24 Port',
            'merk' => 'Cisco',
            'tipe_model' => 'SF250-24',
            'satuan' => 'unit',
            'spesifikasi' => '24 Port Gigabit Ethernet',
            'kategori_id' => 3,
            'estimasi_harga' => 3500000,
            'has_serial_number' => true,
        ]);

        ItemTemplate::create([
            'nama' => 'Kabel UTP Cat6',
            'merk' => 'Belden',
            'tipe_model' => 'Cat6 UTP',
            'satuan' => 'meter',
            'spesifikasi' => 'Kabel UTP Cat6 4 Pair',
            'kategori_id' => 4,
            'estimasi_harga' => 15000,
            'has_serial_number' => false,
        ]);

        ItemTemplate::create([
            'nama' => 'Meja Komputer',
            'merk' => 'Olympic',
            'tipe_model' => 'MK-01',
            'satuan' => 'unit',
            'spesifikasi' => 'Meja komputer dengan rak keyboard',
            'kategori_id' => 6,
            'estimasi_harga' => 750000,
            'has_serial_number' => false,
        ]);

        ItemTemplate::create([
            'nama' => 'Kursi Kantor',
            'merk' => 'Olympic',
            'tipe_model' => 'KC-01',
            'satuan' => 'unit',
            'spesifikasi' => 'Kursi kantor ergonomis dengan sandaran',
            'kategori_id' => 6,
            'estimasi_harga' => 550000,
            'has_serial_number' => false,
        ]);
    }
}
