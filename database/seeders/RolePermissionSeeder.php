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
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $addUsers = Permission::firstOrCreate(['name' => 'add_users']);
        $editUsers = Permission::firstOrCreate(['name' => 'edit_users']);

        $admin->givePermissionTo([$addUsers, $editUsers]);
    }
}
