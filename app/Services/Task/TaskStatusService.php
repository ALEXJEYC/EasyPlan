<?php
namespace App\Services\Task;

use App\Enums\TaskStatus;
use App\Models\TaskUser;
use App\Models\TaskReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskStatusService
{
    public function changeStatus(TaskUser $taskUser, string $newStatus, ?string $comments = null): void
    {
        // Validar la transición usando el Enum
        if (!TaskStatus::isValidTransition($taskUser->status, $newStatus)) {
            throw new \InvalidArgumentException("Transición inválida de {$taskUser->status} a {$newStatus}");
        }

        DB::transaction(function () use ($taskUser, $newStatus, $comments) {
            // Actualizar el estado en la tabla task_user
            $taskUser->update(['status' => $newStatus]);

            // Si el nuevo estado es uno de los estados de revisión, crear un registro en task_reviews
            if (in_array($newStatus, [TaskStatus::APPROVED->value, TaskStatus::REJECTED->value, TaskStatus::NEEDS_REVISION->value])) {
                TaskReview::create([
                    'task_user_id' => $taskUser->id,
                    'status' => $newStatus, // Usar el estado de revisión
                    'comments' => $comments,
                    'reviewed_by' => Auth::id() // Usar el usuario autenticado como revisor
                ]);

                // Opcional: Si la tarea principal debe marcarse como completada cuando *todas* las asignaciones están aprobadas
                // Esto requiere lógica adicional para verificar todas las asignaciones de la tarea.
                // if ($newStatus === TaskStatus::APPROVED->value) {
                //     $task = $taskUser->task;
                //     if ($task->taskUsers()->where('status', '!=', TaskStatus::APPROVED->value)->doesntExist()) {
                //         $task->update(['status' => TaskStatus::COMPLETED->value]);
                //     }
                // }
            }
        });
    }

    // Este método debe obtener las métricas de la tabla task_user
    public function getStatusMetrics(int $projectId): array
    {
        return TaskUser::whereHas('task', fn($q) => $q->where('project_id', $projectId))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    // Este método no parece usarse en los componentes proporcionados,
    // pero si se usa en TaskController, necesitaría ser implementado.
    // public function updateTaskStatusBasedOnReview(int $taskUserId, string $reviewStatus): void
    // {
    //     $taskUser = TaskUser::findOrFail($taskUserId);
    //     // Aquí podrías mapear el estado de la revisión al estado del TaskUser
    //     // Por ejemplo, si reviewStatus es 'approved', newStatus es 'approved'
    //     // Si reviewStatus es 'rejected', newStatus es 'rejected'
    //     // Si reviewStatus es 'needs_revision', newStatus es 'needs_revision' o 'in_progress' (depende del flujo)
    //     // Luego llamas a $this->changeStatus($taskUser, $newStatus);
    // }
}

