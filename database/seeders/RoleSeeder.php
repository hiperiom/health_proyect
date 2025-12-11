<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::create(['name' => 'Administrador']);
        $role_patient = Role::create(['name' => 'Paciente']);

        $permission_create_role = Permission::create(['name' => 'create roles']);
        $permission_read_role = Permission::create(['name' => 'read roles']);
        $permission_update_role = Permission::create(['name' => 'update roles']);
        $permission_delete_role = Permission::create(['name' => 'delete roles']);

        $permission_create_user = Permission::create(['name' => 'create users']);
        $permission_read_user = Permission::create(['name' => 'read users']);
        $permission_update_user = Permission::create(['name' => 'update users']);
        $permission_delete_user = Permission::create(['name' => 'delete users']);

        $permission_create_user_profile = Permission::create(['name' => 'create user_profile']);
        $permission_read_user_profile = Permission::create(['name' => 'read user_profile']);
        $permission_update_user_profile = Permission::create(['name' => 'update user_profile']);
        $permission_delete_user_profile = Permission::create(['name' => 'delete user_profile']);

        $permissions_Admin = [
            $permission_create_role,
            $permission_read_role,
            $permission_update_role,
            $permission_delete_role,
            $permission_create_user,
            $permission_read_user,
            $permission_update_user,
            $permission_delete_user,
            $permission_create_user_profile,
            $permission_read_user_profile,
            $permission_update_user_profile,
            $permission_delete_user_profile,
        ];

        $permission_patient = [
            $permission_read_user,
            $permission_update_user,
            $permission_delete_user,
            $permission_create_user_profile,
            $permission_read_user_profile,
            $permission_update_user_profile,
            $permission_delete_user_profile,
        ];

        $role_admin->syncPermissions($permissions_Admin);
        $role_patient->syncPermissions($permission_patient);

    }
}
