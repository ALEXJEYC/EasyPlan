<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskReview;
use App\Enums\TaskStatus;

class TaskHistory extends Component
{
    use WithPagination;
    
    public $project;
    public $searchTitle = '';
    public $searchAssignedUser = '';
    public $searchApprover = '';
    public $searchDate = '';
    
    protected $paginationTheme = 'tailwind';
    protected $listeners = ['taskApproved' => 'refreshList'];

    public function refreshList()
    {
        $this->resetPage();
    }

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    
    public function clearFilters()
    {
        $this->reset(['searchTitle', 'searchAssignedUser', 'searchApprover', 'searchDate']);
        $this->resetPage();
    }
    public function render()
    {
        $tasks = Task::with(['taskUsers.user', 'reviews' => function ($query) {
                $query->where('task_reviews.status', 'approved')->with('reviewer');
            }])
            ->where('project_id', $this->project->id)
            ->where('tasks.status', TaskStatus::APPROVED->value) // tabla especificada
            ->when($this->searchTitle, function ($query) {
                $query->where('title', 'like', '%'.$this->searchTitle.'%');
            })
            ->when($this->searchAssignedUser , function ($query) {
                $query->whereHas('taskUsers.user', function ($q) {
                    $q->where('name', 'like', '%'.$this->searchAssignedUser .'%');
                });
            })
            ->when($this->searchApprover, function ($query) {
                $query->whereHas('reviews', function ($q) {
                    $q->where('task_reviews.status', 'approved')
                    ->whereHas('reviewer', function ($q2) {
                        $q2->where('name', 'like', '%'.$this->searchApprover.'%');
                    });
                });
            })
            ->when($this->searchDate, function ($query) {
                $query->whereDate('updated_at', $this->searchDate);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        return view('livewire.task-history', compact('tasks'));
    }

}