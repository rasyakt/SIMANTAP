<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'lokasi.list', 'lokasi.create', 'lokasi.edit', 'lokasi.delete',
            'kategori.list', 'kategori.create', 'kategori.edit', 'kategori.delete',
            'template.list', 'template.create', 'template.edit', 'template.delete',
            'barang.list', 'barang.create', 'barang.edit', 'barang.delete',
            'barang.import', 'barang.export',
            'stok.list', 'stok.create', 'stok.edit', 'stok.delete',
            'stok.masuk', 'stok.keluar',
            'perbaikan.list', 'perbaikan.create', 'perbaikan.edit', 'perbaikan.delete',
            'laporan.view', 'laporan.export',
            'pengaturan.view', 'pengaturan.edit',
            'pengguna.list', 'pengguna.create', 'pengguna.edit', 'pengguna.delete',
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'lokasi.list', 'lokasi.create', 'lokasi.edit',
            'kategori.list', 'kategori.create', 'kategori.edit',
            'template.list', 'template.create', 'template.edit',
            'barang.list', 'barang.create', 'barang.edit',
            'barang.import', 'barang.export',
            'stok.list', 'stok.create', 'stok.edit',
            'stok.masuk', 'stok.keluar',
            'perbaikan.list', 'perbaikan.create', 'perbaikan.edit',
            'laporan.view', 'laporan.export',
            'pengaturan.view', 'pengaturan.edit',
            'dashboard.view',
        ]);

        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'lokasi.list',
            'kategori.list',
            'template.list',
            'barang.list', 'barang.create', 'barang.edit',
            'stok.list',
            'perbaikan.list', 'perbaikan.create',
            'laporan.view',
            'dashboard.view',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Pimpinan', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'lokasi.list',
            'kategori.list',
            'barang.list',
            'stok.list',
            'perbaikan.list',
            'laporan.view', 'laporan.export',
            'dashboard.view',
        ]);

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@simantap.test', 'role' => 'Super Admin'],
            ['name' => 'Admin Lab', 'email' => 'admin@simantap.test', 'role' => 'Admin'],
            ['name' => 'Staff Inventaris', 'email' => 'staff@simantap.test', 'role' => 'Staff'],
            ['name' => 'Pimpinan', 'email' => 'pimpinan@simantap.test', 'role' => 'Pimpinan'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                    'phone' => '081234567890',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
            $user->assignRole($userData['role']);
        }
    }
}
