<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectList extends Component
{
    public $isModalOpen = false;
    public $organization;
    public $projects;
    
    protected $listeners = ['projectCreated' => 'handleProjectCreated'];
    
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

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function handleProjectCreated($projectId)
    {
        $this->loadProjects();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.project-list');
    }
}