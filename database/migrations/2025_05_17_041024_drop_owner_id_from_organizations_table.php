<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Primero elimina la foreign key
            $table->dropForeign(['owner_id']);
            
            // Luego elimina la columna
            $table->dropColumn('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Volver a agregar la columna owner_id
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
        });
    }
};
