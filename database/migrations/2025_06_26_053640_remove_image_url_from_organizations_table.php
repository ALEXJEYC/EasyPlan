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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }

    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('image_url')->nullable();
        });
    }
};
