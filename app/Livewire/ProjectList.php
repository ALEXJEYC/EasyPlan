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
    public function handleProjectCreated()
    {
        $this->refreshProjects();
        $this->closeModal();
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
