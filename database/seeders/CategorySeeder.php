<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $komputer = Category::create([
            'nama' => 'Komputer',
            'slug' => 'komputer',
            'deskripsi' => 'Perangkat komputer dan laptop',
            'icon' => 'desktop',
            'is_active' => true,
        ]);

        $monitor = Category::create([
            'nama' => 'Monitor',
            'slug' => 'monitor',
            'deskripsi' => 'Monitor layar komputer',
            'icon' => 'display',
            'is_active' => true,
        ]);

        $jaringan = Category::create([
            'nama' => 'Jaringan',
            'slug' => 'jaringan',
            'deskripsi' => 'Perangkat jaringan dan konektivitas',
            'icon' => 'network',
            'is_active' => true,
        ]);

        $kabel = Category::create([
            'nama' => 'Kabel',
            'slug' => 'kabel',
            'deskripsi' => 'Kabel dan aksesoris kabel',
            'icon' => 'cable',
            'is_active' => true,
        ]);

        $elektronik = Category::create([
            'nama' => 'Elektronik',
            'slug' => 'elektronik',
            'deskripsi' => 'Perangkat elektronik umum',
            'icon' => 'electronic',
            'is_active' => true,
        ]);

        $furniture = Category::create([
            'nama' => 'Furniture',
            'slug' => 'furniture',
            'deskripsi' => 'Meja, kursi, lemari dan perabotan',
            'icon' => 'chair',
            'is_active' => true,
        ]);

        $atk = Category::create([
            'nama' => 'Alat Tulis Kantor',
            'slug' => 'alat-tulis-kantor',
            'deskripsi' => 'Peralatan tulis dan kantor',
            'icon' => 'pen',
            'is_active' => true,
        ]);

        Category::create([
            'nama' => 'CPU',
            'slug' => 'cpu',
            'deskripsi' => 'Unit pemrosesan pusat untuk komputer',
            'parent_id' => $komputer->id,
            'icon' => 'cpu',
            'is_active' => true,
        ]);

        Category::create([
            'nama' => 'Kabel LAN',
            'slug' => 'kabel-lan',
            'deskripsi' => 'Kabel jaringan LAN',
            'parent_id' => $kabel->id,
            'icon' => 'lan',
            'is_active' => true,
        ]);
    }
}
