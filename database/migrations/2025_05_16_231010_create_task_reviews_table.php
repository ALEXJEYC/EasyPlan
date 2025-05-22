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
        Schema::create('task_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // usuario que revisa la tarea
            $table->timestamp('reviewed_at')->nullable(); // cuando fue revisada la tarea
            $table->foreignId('task_user_id')->constrained('task_user')->onDelete('cascade'); 
            $table->unique('task_user_id'); 
            $table->enum('status', ['approved', 'rejected', 'needs_revision'])->default('needs_revision');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reviews');
    }
};
