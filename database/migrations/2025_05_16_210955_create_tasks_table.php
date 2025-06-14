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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('deadline')->nullable(); //fecha limite que se le da a la tarea
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('ready', ['yes', 'no'])->default('no'); // si la tarea esta lista para ser revisada
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // prioridad de la tarea
            // $table->timestamp('submitted_at')->nullable();
            // $table->text('observation')->nullable();
            // $table->foreignId('submitted_by')->nullable()->constrained('users');
            
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
