<?php

namespace App\Livewire;

// <?php

// namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;

class ProjectOverview extends Component
{
    public $project;
    public $progress;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->calculateProgress();
    }

    private function calculateProgress()
    {
        $totalTasks = $this->project->tasks()->count();
        $approvedTasks = TaskUser::whereHas('task', function ($query) {
            $query->where('project_id', $this->project->id);
        })->where('status', 'approved')->count();

        $this->progress = $totalTasks > 0 ? round(($approvedTasks / $totalTasks) * 100) : 0;
    }

    public function render()
    {
        return view('livewire.project-overview');
    }
}