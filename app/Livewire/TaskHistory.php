<?php

namespace App\Livewire;

// <?php

// namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;

class TaskHistory extends Component
{
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $tasks = TaskUser::whereHas('task', function ($query) {
            $query->where('project_id', $this->project->id);
        })->where('status', 'approved')->get();

        return view('livewire.task-history', compact('tasks'));
    }
}