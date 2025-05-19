<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectList extends Component
{
    public $isModalOpen = false;
    public $organization;
    public $projects;
    public $projectCreatedEventReceived = false; 
    protected $listeners = ['projectCreated' => 'refreshProjects'];
    public function handleProjectCreated()
    {
        if ($this->projectCreatedEventReceived) {
            $this->refreshProjects();
            $this->closeModal();
            $this->projectCreatedEventReceived = false; // Resetear
        }
    }
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
        $this->projectCreatedEventReceived = false; // << Importante
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }


    public function refreshProjects()
    {
        $this->projects = $this->organization->projects()->latest()->get();
        $this->isModalOpen = false;
    }

    public function render() {
        return view('livewire.project-list', [
            'projects' => $this->projects,
        ]);
    }

}
