<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Services\Task\TaskStatusService;
use App\Enums\TaskStatus;   
use App\Models\TaskReview;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TaskReviewPanel extends Component
{
    public $project;
    public $selectedReviews = [];
    protected $taskStatusService;
    // public Project $project;
    public string $comments = '';
    protected TaskStatusService $statusService;
    public $selectedTask = null;
    public $statusMetrics = [];
    public bool $showDetailsModal = false;
    public $tasks;
     protected $listeners = [
        'tasksUpdated' => 'loadTasksForReview',
        'refreshPanel' => '$refresh',
        'taskCreated' => 'loadTasksForReview',
        'taskSubmitted' => 'loadTasksForReview',
        'taskApproved' => 'loadTasksForReview',
        'taskRejected' => 'loadTasksForReview',
    ];

    
     public function boot(TaskStatusService $statusService)
     {
         $this->statusService = $statusService;
     }

    
    
    public function mount(Project $project)
    {
        $this->project = $project;
        $this->statusService = new TaskStatusService();
        // $this->statusMetrics = $this->statusService->getStatusMetrics($this->project->id);
        $this->loadMetrics();
        $this->loadTasksForReview();
        
    }
    public function canreviewTasks(): bool
    {
        // Verifica si el usuario tiene permiso para revisar tareas en el proyecto actual
        return Auth::user()->hasPermissionInOrganization('Review_tasks', $this->project->organization_id);
    }
    
    // public function __construct()
    // {
    //     $this->taskStatusService = new TaskStatusService();
    // }
public function approveTask(int $taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        
        // Validar transición
        if (!TaskStatus::isValidTransition($task->status->value, TaskStatus::APPROVED->value)) {
            throw new \InvalidArgumentException("Transición inválida de {$task->status} a approved");
        }

DB::transaction(function () use ($task) {
    // Buscar TaskUser correspondiente
    $taskUser = $task->taskUsers()
        ->where('user_id', Auth::id())
        ->first();

if (!$taskUser) {
    // Crear la relación si no existe
    $taskUser = $task->taskUsers()->create([
        'user_id' => Auth::id(),
        'status' => TaskStatus::APPROVED->value,
    ]);
}

    // Aprobar la tarea principal
        $task->update([
            'status' => TaskStatus::APPROVED->value,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Crear la revisión
        TaskReview::create([
            'task_id' => $task->id,
            'task_user_id' => $taskUser->id, // ✅ Aquí se incluye
            'reviewed_by' => Auth::id(),
            'status' => TaskStatus::APPROVED->value,
            'comments' => $this->comments ?? null,
            'reviewed_at' => now(),
        ]);

        // Aprobar todos los task_users (opcional)
        $task->taskUsers()->update(['status' => TaskStatus::APPROVED->value]);
    });

        $this->dispatch('notify', 
            type: 'success', 
            message: 'Tarea aprobada exitosamente'
        );
        $this->dispatch('taskApproved', projectId: $this->project->id);
        $this->dispatch('tasksUpdated');         
        $this->refreshMetrics();
        $this->loadTasksForReview();

    } catch (\Exception $e) {
        $this->dispatch('notify',
            type: 'error',
            message: $e->getMessage()
        );
    }
}
public function loadMetrics()
{
    $this->statusMetrics = Task::where('project_id', $this->project->id)
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();
}

    public function viewDetails($taskReviewId) // Cambiado a taskReviewId si selectedTask es TaskReview
    {
         // Si selectedTask es TaskReview, busca por TaskReview ID
        $this->selectedTask = TaskReview::with(['taskUser.user', 'taskUser.task', 'evidences', 'reviews.reviewer'])
            ->findOrFail($taskReviewId);

        // Si selectedTask es TaskUser, busca por TaskUser ID
        // $this->selectedTask = TaskUser::with(['task', 'user', 'evidences', 'reviews.reviewer'])
        //     ->findOrFail($taskUserId); // Usar $taskUserId si este es el caso

        $this->showDetailsModal = true;
    }

    public function generateReport()
    {
        // Lógica para generar reporte
        $this->dispatch('notify', 
            type: 'info', 
            message: 'Reporte generado con éxito'
        );
    }
    public function loadTasksForReview()
    {
        $this->tasks = Task::with(['taskUsers.user', 'submittedBy'])
            ->where('project_id', $this->project->id)
            ->where('status', TaskStatus::SUBMITTED->value)
            ->latest()
            ->get();
    }

public function rejectTask(int $taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        
        if (!TaskStatus::isValidTransition($task->status->value, TaskStatus::REJECTED->value)) {
            throw new \InvalidArgumentException("Transición inválida de {$task->status} a rejected");
        }

        DB::transaction(function () use ($task) {
            $task->update([
                'status' => TaskStatus::REJECTED->value,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $this->comments
            ]);
            // Actualizar SOLO el TaskUser del usuario actual
            $task->taskUsers()
                ->where('user_id', auth()->id()) // Filtra por el usuario actual
                ->update([
                    'status' => TaskStatus::REJECTED->value,
                ]);
        
        });

        $this->dispatch('notify', 
            type: 'success', 
            message: 'Tarea rechazada exitosamente'
        );
        $this->dispatch('tasksUpdated'); 
        $this->dispatch('taskRejected', projectId: $this->project->id);
        
        
        $this->refreshMetrics();
        $this->loadTasksForReview();

    } catch (\Exception $e) {
        $this->dispatch('notify',
            type: 'error',
            message: $e->getMessage()
        );
    } finally {
        $this->comments = '';
    }
}
// public function completeTask(int $taskUserId)
// {
//     try {
//         $taskUser = TaskUser::findOrFail($taskUserId);
        
//         if ($taskUser->status !== TaskStatus::APPROVED->value) {
//             $this->dispatch('notify', type: 'error', message: 'Solo se pueden completar tareas aprobadas');
//             return;
//         }

//         $this->statusService->changeStatus(
//             $taskUser,
//             TaskStatus::APPROVED->value,
//             $this->comments
//         );

//         $this->dispatch('notify', type: 'success', message: 'Tarea marcada como completada');
//         $this->refreshMetrics();
//         $this->loadTasksForReview();
//     } catch (\Exception $e) {
//         $this->dispatch('notify', type: 'error', message: $e->getMessage());
//     } finally {
//         $this->comments = '';
//     }
// }


    
private function refreshMetrics()
{
    $this->loadMetrics(); // Carga ya la colección correctamente
    $this->dispatch('tasksUpdated');
}



public function render()

{
    return view('livewire.task-review-panel', [
        'tasks' => $this->tasks // Usa $this->tasks ya cargado en loadTasksForReview()
    ]);
}
}