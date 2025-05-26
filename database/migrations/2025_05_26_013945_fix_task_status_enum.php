<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTaskStatusEnum extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM(
            'pending',
            'submitted',
            'needs_revision',
            'rejected'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM(
            'in_progress',
            'submitted',
            'needs_revision',
            'rejected'
        ) DEFAULT NULL");
    }
}