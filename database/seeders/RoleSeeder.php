<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $doctorRole = Role::create(['name' => 'doctor']);
        $receptionist = Role::create(['name' => 'receptionist']);


        $readUserPermission = Permission::create(['name' => 'read-users']);
        $createUserPermission = Permission::create(['name' => 'create-users']);
        $updateUserPermission = Permission::create(['name' => 'update-users']);
        $deleteUserPermission = Permission::create(['name' => 'delete-users']);

        $readSpecialtyPermission = Permission::create(['name' => 'read-specialties']);
        $createSpecialtyPermission = Permission::create(['name' => 'create-specialties']);
        $updateSpecialtyPermission = Permission::create(['name' => 'update-specialties']);
        $deleteSpecialtyPermission = Permission::create(['name' => 'delete-specialties']);

        $readServicePermission = Permission::create(['name' => 'read-services']);
        $createServicePermission = Permission::create(['name' => 'create-services']);
        $updateServicePermission = Permission::create(['name' => 'update-services']);
        $deleteServicePermission = Permission::create(['name' => 'delete-services']);

        $readCasePermission = Permission::create(['name' => 'read-cases']);
        $createCasePermission = Permission::create(['name' => 'create-cases']);
        $updateCasePermission = Permission::create(['name' => 'update-cases']);
        $deleteCasePermission = Permission::create(['name' => 'delete-cases']);

        $adminPermissions = [
            $readUserPermission, $createUserPermission, $updateUserPermission, $deleteUserPermission,
            $readSpecialtyPermission, $createSpecialtyPermission, $updateSpecialtyPermission, $deleteSpecialtyPermission,
            $readServicePermission, $createServicePermission, $updateServicePermission, $deleteServicePermission,
            $readCasePermission, $createCasePermission, $updateCasePermission, $deleteCasePermission,
        ];

        $receptionistPermissions = [
            $readUserPermission, $readSpecialtyPermission, $createSpecialtyPermission, $updateSpecialtyPermission, $deleteSpecialtyPermission,
            $readServicePermission, $createServicePermission, $updateServicePermission, $deleteServicePermission,
        ];

        $doctorPermissions = [
	        $readUserPermission,
            $readSpecialtyPermission, $createSpecialtyPermission, $updateSpecialtyPermission, $deleteSpecialtyPermission,
            $readServicePermission, $createServicePermission, $updateServicePermission, $deleteServicePermission,
            $readCasePermission, $createCasePermission, $updateCasePermission, $deleteCasePermission,
        ];

        $adminRole->syncPermissions($adminPermissions);
        $receptionist->syncPermissions($receptionistPermissions);
        $doctorRole->syncPermissions($doctorPermissions);
        




   
    }
}
