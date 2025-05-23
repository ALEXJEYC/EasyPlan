<?php

namespace App\Livewire;

// <?php

// namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;

class TaskReviewPanel extends Component
{
    public $project;
    public $selectedReviews = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

public function approveTask($taskUserId)
{
    $taskUser = TaskUser::find($taskUserId);
    $taskUser->update([
        'status' => 'approved',
        'reviewed_at' => now(),
        'reviewed_by' => auth()->id()
    ]);
    
    $this->dispatch('notify', 
        type: 'success', 
        message: 'Tarea aprobada correctamente'
    );
}

public function rejectTask($taskUserId)
{
    $taskUser = TaskUser::find($taskUserId);
    $taskUser->update([
        'status' => 'rejected',
        'reviewed_at' => now(),
        'reviewed_by' => auth()->id()
    ]);
    
    $this->dispatch('notify', 
        type: 'success', 
        message: 'Tarea rechazada correctamente'
    );
}

    public function render()
    {
        $tasks = TaskUser::whereHas('task', function ($query) {
            $query->where('project_id', $this->project->id);
        })->where('status', 'submitted')->get();

        return view('livewire.task-review-panel', compact('tasks'));
    }
}