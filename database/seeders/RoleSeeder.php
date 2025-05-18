<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Primero asegúrate que los permisos están creados (o ejecuta el PermissionSeeder antes)
        $permissions = Permission::all();

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->syncPermissions($permissions); // Le asignamos todos los permisos existentes al owner
    }

}