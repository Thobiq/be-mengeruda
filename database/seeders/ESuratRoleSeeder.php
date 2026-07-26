<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class ESuratRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions for E-Surat
        $permissions = [
            'manage-surat' => 'Mengelola Verifikasi Akun & Pengajuan Surat Desa',
            'request-surat' => 'Mengajukan Permohonan Surat Resmi Desa',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // 2. Create Warga role
        $wargaRole = Role::firstOrCreate(
            ['name' => 'warga'],
            ['description' => 'Warga Desa Mengeruda pemohon E-Surat']
        );
        $wargaPerm = Permission::where('name', 'request-surat')->first();
        if ($wargaPerm) {
            $wargaRole->permissions()->syncWithoutDetaching([$wargaPerm->id]);
        }

        // 3. Create admin_surat role
        $adminSuratRole = Role::firstOrCreate(
            ['name' => 'admin_surat'],
            ['description' => 'Administrator Pelayanan Surat & Verifikasi Akun Warga']
        );
        $adminPerm = Permission::where('name', 'manage-surat')->first();
        if ($adminPerm) {
            $adminSuratRole->permissions()->syncWithoutDetaching([$adminPerm->id]);
        }

        // 4. Also grant manage-surat to Super Admin role if exists
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin && $adminPerm) {
            $superAdmin->permissions()->syncWithoutDetaching([$adminPerm->id]);
        }
    }
}
