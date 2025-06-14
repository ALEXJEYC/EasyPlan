<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_user', function (Blueprint $table) {
            // Primero eliminamos la foreign key
            $table->dropForeign(['reviewed_by']);
            // Luego la columna
            $table->dropColumn('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete(); // Por si quieres mantener el comportamiento limpio
        });
    }
};
