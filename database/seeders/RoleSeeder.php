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
        $role_admin = Role::create(['name' => 'Administrador', 'color' => 'blue']);
        $role_patient = Role::create(['name' => 'Paciente', 'color' => 'green']);
        $role_medic = Role::create(['name' => 'Medico', 'color' => 'primary']);
        $role_nurse = Role::create(['name' => 'Enfermería', 'color' => 'warning']);
        $role_patient_atention = Role::create(['name' => 'Atención al Paciente', 'color' => 'danger']);
        

        $permission_create_role = Permission::create(['name' => 'create roles']);
        $permission_read_role = Permission::create(['name' => 'read roles']);
        $permission_update_role = Permission::create(['name' => 'update roles']);
        $permission_delete_role = Permission::create(['name' => 'delete roles']);

        $permission_create_user = Permission::create(['name' => 'create users']);
        $permission_read_user = Permission::create(['name' => 'read users']);
        $permission_update_user = Permission::create(['name' => 'update users']);
        $permission_delete_user = Permission::create(['name' => 'delete users']);

        $permission_create_user_profile = Permission::create(['name' => 'create user-profile']);
        $permission_read_user_profile = Permission::create(['name' => 'read user-profile']);
        $permission_update_user_profile = Permission::create(['name' => 'update user-profile']);
        $permission_delete_user_profile = Permission::create(['name' => 'delete user-profile']);

        $permission_create_medic_especialities = Permission::create(['name' => 'create medic-especialities']);
        $permission_read_medic_especialities = Permission::create(['name' => 'read medic-especialities']);
        $permission_update_medic_especialities = Permission::create(['name' => 'update medic-especialities']);
        $permission_delete_medic_especialities = Permission::create(['name' => 'delete medic-especialities']);

        $permission_create_medics = Permission::create(['name' => 'create medics']);
        $permission_read_medics = Permission::create(['name' => 'read medics']);
        $permission_update_medics = Permission::create(['name' => 'update medics']);
        $permission_delete_medics = Permission::create(['name' => 'delete medics']);
        
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
            $permission_create_medic_especialities,
            $permission_read_medic_especialities,
            $permission_update_medic_especialities,
            $permission_delete_medic_especialities,
            $permission_create_medics,
            $permission_read_medics,
            $permission_update_medics,
            $permission_delete_medics,
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
