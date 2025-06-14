<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanInvalidTaskStatuses extends Migration
{
    public function up(): void
    {
        // Cambiar valores inválidos a 'pending'
        DB::table('tasks')
            ->whereNotIn('status', ['pending', 'submitted', 'needs_revision', 'rejected'])
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {
        // No necesario a menos que quieras revertir cambios
    }
}