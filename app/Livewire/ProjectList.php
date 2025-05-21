<?php

// namespace App\Livewire;
namespace App\Livewire;

use Livewire\Component;

class ProjectList extends Component
{
    public $organization;
    public $projects;

    protected $listeners = ['projectCreated' => 'loadProjects'];

    public function mount($organization)
    {
        $this->organization = $organization;
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = $this->organization
            ->projects()
            ->forUser(auth()->id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.project-list');
    }
}
