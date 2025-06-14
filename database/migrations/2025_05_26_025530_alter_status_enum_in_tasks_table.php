<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterStatusEnumInTasksTable extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE tasks 
            MODIFY status ENUM('pending', 'submitted', 'needs_revision', 'rejected', 'approved') 
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE tasks 
            MODIFY status ENUM('pending', 'submitted', 'needs_revision', 'rejected') 
            NOT NULL DEFAULT 'pending'
        ");
    }
}
