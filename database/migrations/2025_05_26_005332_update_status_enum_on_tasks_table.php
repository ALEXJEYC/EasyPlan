<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cambiar el ENUM para incluir solo los nuevos valores permitidos
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM(
            'pending', 
            'submitted', 
            'needs_revision', 
            'rejected'
        ) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al estado anterior (antes de esta migración)
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM(
            'pending', 
            'in_progress', 
            'submitted', 
            'completed'
        ) DEFAULT 'pending'");
    }
};