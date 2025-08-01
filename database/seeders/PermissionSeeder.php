<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            // 'eliminar_organizacion',
            // 'asignar_tareas',
            // 'editar_tareas',
            // 'puede_tener_tareas',
            'Manage_projects',
            'can_transfer_organization',
            'create_roles',
            'create_tasks',
            'Review_tasks',
            'manage_roles',
            'create_project',
            'add_members',
            'remove_members',
        ];
        

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}