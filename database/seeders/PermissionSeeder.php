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
            // 'agregar_usuarios',
            // 'editar_tareas',
            // 'recibir_tareas',
            // 'puede_tener_tareas',
            // 'transferir_organizacion',
            'manage_roles',
            // 'crear_proyectos',
            'add_members',
            'remove_members',
        ];
        

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}