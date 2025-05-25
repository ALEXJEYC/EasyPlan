<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use Livewire\WithPagination;

class TaskHistory extends Component
{
    use WithPagination;
    
    public $project;
    public $searchTitle = '';   
    public $searchAssignedUser = '';
    public $searchApprover = '';
    public $searchDate = '';
    public $searchFields = [
    'Título' => '',
    'Aprobador' => '',
    'Asignadoa' => '',
    'Fecha' => ''
];

    protected $paginationTheme = 'tailwind';

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    
    public function clearFilters()
    {
        $this->reset(['searchTitle', 'searchAssignedUser', 'searchApprover', 'searchDate']);
        $this->resetPage();
    }

    public function updating($property)
    {
        // Resetear paginación para cualquier cambio en los filtros
        if (in_array($property, ['searchTitle', 'searchAssignedUser', 'searchApprover', 'searchDate'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $tasks = TaskUser::with(['user', 'task', 'latestReview.reviewer'])
            ->whereHas('task', function ($query) {
                $query->where('project_id', $this->project->id)
                      ->when($this->searchTitle, fn($q) => $q->where('title', 'like', '%'.$this->searchTitle.'%'));
            })
            ->where('status', 'approved')
            ->when($this->searchAssignedUser, function ($query) {
                $query->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$this->searchAssignedUser.'%'));
            })
            ->when($this->searchApprover, function ($query) {
                $query->whereHas('latestReview.reviewer', fn($q) => $q->where('name', 'like', '%'.$this->searchApprover.'%'));
            })
            ->when($this->searchDate, function ($query) {
                $query->whereDate('updated_at', $this->searchDate);
            })
            ->paginate(5);

        return view('livewire.task-history', compact('tasks'));
    }
}