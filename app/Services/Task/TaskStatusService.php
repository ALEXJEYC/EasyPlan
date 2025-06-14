<?php
namespace App\Services\Task;

use App\Enums\TaskStatus;
use App\Models\TaskUser;
use App\Models\TaskReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskStatusService
{
public function changeStatus(TaskUser $taskUser, string $newStatus, ?string $comments = null, ?int $reviewedBy = null): void
{
    if (! TaskStatus::isValidTransition($taskUser->status, $newStatus)) {
        throw new \InvalidArgumentException("Transición inválida de {$taskUser->status} a {$newStatus}");
    }

    $reviewer = $reviewedBy ?? Auth::id();
    if (! $reviewer) {
        throw new \Exception("No hay usuario autenticado para el reviewed_by");
    }

    DB::transaction(function () use ($taskUser, $newStatus, $comments, $reviewer) {
        $taskUser->update(['status' => $newStatus]);

        if (in_array($newStatus, [
            TaskStatus::APPROVED->value,
            TaskStatus::REJECTED->value,
            TaskStatus::NEEDS_REVISION->value,
        ])) {
            TaskReview::create([
                'task_user_id' => $taskUser->id,
                'status'       => $newStatus,
                'comments'     => $comments,
                'reviewed_by'  => $reviewer,
            ]);
        }
    });
}


    // Este método debe obtener las métricas de la tabla task_user
public function getStatusMetrics(int $projectId): array
{
    $query = TaskUser::whereHas('task', fn($q) => $q->where('project_id', $projectId))
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    $allStatuses = collect(TaskStatus::cases())->pluck('value');
    
    $metrics = [];

    foreach ($allStatuses as $status) {
        $metrics[$status] = $query[$status] ?? 0;
    }

    return $metrics;
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


//✅ SÍ se conecta con la base de datos al:
// Actualizar el modelo TaskUser.
// Crear un nuevo TaskReview.

