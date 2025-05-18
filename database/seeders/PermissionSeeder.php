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
            'eliminar_organizacion',
            'asignar_tareas',
            'eliminar_usuarios',
            'agregar_usuarios',
            'editar_tareas',
            'recibir_tareas',
            'puede_tener_tareas',
            'transferir_organizacion'
        ];
        

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}