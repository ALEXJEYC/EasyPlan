<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Task\TaskStatusService;
use App\Models\TaskReview;

class TaskController extends Controller
{
    protected $taskStatusService;

    // Inyectamos el servicio en el constructor
    public function __construct(TaskStatusService $taskStatusService)
    {
        $this->taskStatusService = $taskStatusService;
    }

    // Método para crear una revisión y actualizar el estado de la tarea
    public function reviewTask(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|integer|exists:tasks,id',
            'reviewer_id' => 'required|integer|exists:users,id',
            'task_user_id' => 'required|integer', // verifica si necesitas validar existencia en tabla pivot
            'status' => 'required|in:approved,rejected,needs_revision',
        ]);

        // Crear la revisión
        $review = TaskReview::create([
            'task_id' => $validated['task_id'],
            'reviewer_id' => $validated['reviewer_id'],
            'task_user_id' => $validated['task_user_id'],
            'status' => $validated['status'],
            'reviewed_at' => now(),
        ]);

        // Actualizar estado de la tarea en base a la revisión
        $this->taskStatusService->updateTaskStatusBasedOnReview($validated['task_user_id'], $validated['status']);

        return response()->json(['message' => 'Revisión creada y estado actualizado', 'review' => $review]);
    }
}
