<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('task_evidences', function (Blueprint $table) {
            $table->unsignedBigInteger('file_size')->after('file_name');
            $table->string('file_type')->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down()
    {
        Schema::table('task_evidences', function (Blueprint $table) {
            $table->dropColumn(['file_size', 'file_type']);
        });
    }
};
