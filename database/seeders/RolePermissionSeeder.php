<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        Role::firstOrCreate(['name' => 'użytkownik', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'administrator', 'guard_name' => 'web']);

        //Permission Create
        Permission::firstOrCreate(['name' => 'videos.moderate', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'videos.view', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'users.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.delete', 'guard_name' => 'web']);

    }
}
