<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SettingSeeder::class,
            LocationSeeder::class,
            CategorySeeder::class,
            ItemTemplateSeeder::class,
            ItemSeeder::class,
            StockSeeder::class,
        ]);
    }
}
