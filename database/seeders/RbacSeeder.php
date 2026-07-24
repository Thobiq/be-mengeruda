<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Permissions
        $permissions = [
            'manage-users' => 'Mengelola Data Pengguna (Administrator)',
            'manage-roles' => 'Mengelola Peran & Hak Akses',
            'manage-profile' => 'Mengelola Profil Desa',
            'manage-news' => 'Mengelola Berita Desa',
            'manage-demographics' => 'Mengelola Data Demografi',
            'manage-apb' => 'Mengelola APB Desa',
            'manage-map' => 'Mengelola Peta Wilayah',
            'manage-org-chart' => 'Mengelola Struktur Organisasi',
            'manage-gallery' => 'Mengelola Galeri Desa'
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // 2. Create Super Admin Role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            ['description' => 'Memiliki hak akses penuh ke seluruh sistem']
        );

        // Assign all permissions to Super Admin
        $superAdminRole->permissions()->sync(Permission::all());

        // 3. Assign Super Admin Role to existing user ID 1 (if exists)
        $user = User::find(1);
        if ($user) {
            if (!$user->roles->contains($superAdminRole->id)) {
                $user->roles()->attach($superAdminRole->id);
            }
        }
    }
}
