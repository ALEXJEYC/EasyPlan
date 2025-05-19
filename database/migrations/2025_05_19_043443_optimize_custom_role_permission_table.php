<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Eliminar la clave primaria `id` existente
        Schema::table('custom_role_permissions', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // 2. Eliminar timestamps si existen (opcional)
        Schema::table('custom_role_permissions', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        // 3. Añadir clave primaria compuesta
        Schema::table('custom_role_permissions', function (Blueprint $table) {
            $table->primary(['custom_role_id', 'permission_id']);
        });
    }

    public function down()
    {
        // Revertir los cambios (por si necesitas rollback)
        Schema::table('custom_role_permissions', function (Blueprint $table) {
            $table->dropPrimary(['custom_role_id', 'permission_id']);
            $table->id()->first();
            $table->timestamps();
        });
    }
};