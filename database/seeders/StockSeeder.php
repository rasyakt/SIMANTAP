<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        Stock::create([
            'nama' => 'Kabel UTP Cat6 Belden',
            'kategori_id' => 4,
            'item_template_id' => 5,
            'lokasi_id' => 2,
            'jumlah_stok' => 200,
            'ambang_batas_minimum' => 20,
            'satuan' => 'meter',
            'harga_satuan' => 15000,
            'vendor' => 'PT Belden Indonesia',
            'catatan' => 'Stok kabel untuk kebutuhan jaringan lab',
        ]);

        Stock::create([
            'nama' => 'Mouse Optikal USB',
            'kategori_id' => 5,
            'item_template_id' => null,
            'lokasi_id' => 2,
            'jumlah_stok' => 15,
            'ambang_batas_minimum' => 5,
            'satuan' => 'unit',
            'harga_satuan' => 75000,
            'vendor' => 'PT Logitech Indonesia',
            'catatan' => 'Mouse cadangan untuk pengganti rusak',
        ]);

        Stock::create([
            'nama' => 'Keyboard USB Standard',
            'kategori_id' => 5,
            'item_template_id' => null,
            'lokasi_id' => 2,
            'jumlah_stok' => 10,
            'ambang_batas_minimum' => 5,
            'satuan' => 'unit',
            'harga_satuan' => 85000,
            'vendor' => 'PT Logitech Indonesia',
            'catatan' => 'Keyboard cadangan',
        ]);

        Stock::create([
            'nama' => 'RAM DDR4 8GB',
            'kategori_id' => 5,
            'item_template_id' => null,
            'lokasi_id' => 2,
            'jumlah_stok' => 3,
            'ambang_batas_minimum' => 5,
            'satuan' => 'unit',
            'harga_satuan' => 350000,
            'vendor' => 'PT Kingston Indonesia',
            'catatan' => 'RAM untuk upgrade/perbaikan komputer - STOK MENIPIS',
        ]);

        Stock::create([
            'nama' => 'Konektor RJ45',
            'kategori_id' => 4,
            'item_template_id' => null,
            'lokasi_id' => 2,
            'jumlah_stok' => 100,
            'ambang_batas_minimum' => 20,
            'satuan' => 'unit',
            'harga_satuan' => 2000,
            'vendor' => 'PT Network Solution',
            'catatan' => 'Konektor RJ45 untuk kabel LAN',
        ]);
    }
}
