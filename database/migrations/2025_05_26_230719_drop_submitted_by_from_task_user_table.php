<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSubmittedByFromTaskUserTable extends Migration
{
    public function up(): void
    {
        Schema::table('task_user', function (Blueprint $table) {
            // Primero elimina la clave foránea si existe
            $table->dropForeign(['submitted_by']);

            // Luego elimina la columna
            $table->dropColumn('submitted_by');
        });
    }

    public function down(): void
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->unsignedBigInteger('submitted_by')->nullable()->after('status');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
        });
    }
}
