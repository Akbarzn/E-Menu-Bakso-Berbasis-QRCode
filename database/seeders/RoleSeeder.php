<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // buat User super admin
        $user = User::firstOrCreate(
            ['email' => 'super@admin.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $user->assignRole($superAdminRole);

    }
}
