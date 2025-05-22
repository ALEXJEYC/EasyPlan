<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_user_id')->constrained('task_user')->onDelete('cascade'); // Tarea enviada
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // Usuario que envía el feedback
            $table->text('message'); // Contenido del mensaje
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_feedbacks');
    }
};
