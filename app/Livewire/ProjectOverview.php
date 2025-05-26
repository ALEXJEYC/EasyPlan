<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\DB;

class ProjectOverview extends Component
{
    public $project;
    public $progress;
   protected $listeners = [
    'tasksUpdated' => 'render',
    'taskCreated' => 'handleTaskEvent',
    'taskSubmitted' => 'handleTaskEvent',
    'taskApproved' => 'handleTaskEvent',
    'taskRejected' => 'handleTaskEvent',
];


    public function mount(Project $project)
    {
        $this->project = $project;
        $this->calculateProgress();
        // $this->dispatchBrowserEvent('updateChart', ['progress' => $this->progress]);


    }
    public function handleTaskEvent($projectId)
{
    if ($this->project->id === $projectId) {
        $this->calculateProgress();
    }
}
    
public function calculateProgress()
{
    $this->project = $this->project->fresh();

    $totalTasks = $this->project->tasks()->count();
    $approvedTasks = $this->project->tasks()
        ->where('status', TaskStatus::APPROVED->value)
        ->count();

    if ($totalTasks === 0) {
        $this->progress = 0;
    } else {
        // Porcentaje de tareas aprobadas sobre total
        $this->progress = round(($approvedTasks / $totalTasks) * 100);
    }

    // Enviar evento para actualizar el gráfico si usas JS en frontend
    $this->dispatch('updateChartProgress', ['progress' => $this->progress]);
}

    
public function render()
{
    $this->calculateProgress(); // Actualiza antes de renderizar
    return view('livewire.project-overview', ['progress' => $this->progress]);
}
}