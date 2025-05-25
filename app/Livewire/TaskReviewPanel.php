<?php

namespace App\Livewire;

// <?php

// namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Services\Task\TaskStatusService;
use App\Enums\TaskStatus;   
use App\Models\TaskReview;
use App\Models\Task;

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
    protected $listeners = ['tasksUpdated' => 'loadTasksForReview'];

    
    
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
    
    public function __construct()
    {
        $this->taskStatusService = new TaskStatusService();
    }
    public function approveTask(int $taskUserId)
    {
        try {
            $this->statusService->changeStatus(
                TaskUser::findOrFail($taskUserId),
                TaskStatus::APPROVED->value,
                $this->comments
            );

            $this->dispatch('notify',
                type: 'success',
                message: 'Tarea aprobada exitosamente'
            );
            $this->refreshMetrics();
            $this->loadTasksForReview(); // Recargar la lista después de aprobar
        } catch (\Exception $e) {
            $this->dispatch('notify',
                type: 'error',
                message: $e->getMessage()
            );
        } finally {
            $this->comments = '';
        }
    }
    public function loadMetrics()
    {
        // Cambiamos 'task' por 'taskUser.task' para seguir la relación anidada
        $this->statusMetrics = TaskReview::whereHas('taskUser.task', function ($query) {
            $query->where('project_id', $this->project->id);
        })
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');
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
    $this->tasks = TaskUser::whereHas('task', function($query) {
        $query->where('project_id', $this->project->id);
    })
    ->where('status', TaskStatus::SUBMITTED->value)
    ->with(['task', 'user', 'evidences', 'reviews.reviewer'])
    ->get();
}
public function rejectTask($taskUserId)
{
    $taskUser = TaskUser::find($taskUserId);

    if ($taskUser && $taskUser->status !== TaskStatus::REJECTED->value) {
        $this->statusService->changeStatus(
            $taskUser,
            TaskStatus::REJECTED->value,
            'Tarea rechazada'
        );

        $this->refreshMetrics();
        $this->loadTasksForReview();

        session()->flash('message', 'Tarea rechazada correctamente.');
    } else {
        session()->flash('error', 'La tarea ya está rechazada.');
    }
}

    
    private function refreshMetrics()
    {
        // Asegúrate de que este método use la relación anidada si es necesario
        // O si tu servicio ya maneja la lógica correcta
        $this->statusMetrics = $this->statusService->getStatusMetrics($this->project->id);
        $this->loadMetrics(); // Reutiliza el método loadMetrics corregido
        $this->dispatch('tasksUpdated');
    }


public function render()

{
    return view('livewire.task-review-panel', [
        'tasks' => $this->tasks // Usa $this->tasks ya cargado en loadTasksForReview()
    ]);
}
}