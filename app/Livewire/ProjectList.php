<?php

// namespace App\Livewire;
namespace App\Livewire;

use Livewire\Component;

class ProjectList extends Component
{
    public $projects;
    public $organization;
    public bool $showArchived = false;

    protected $listeners = ['projectCreated' => 'loadProjects'];

    public function mount($organization)
    {
        $this->organization = $organization;
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = $this->organization->projects()
            ->with(['organization', 'users'])
            ->active()
            ->get();
    }

    public function render()
    {
        return view('livewire.project-list');
    }
}
