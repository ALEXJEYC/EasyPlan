<?php

namespace App\Livewire;

use Livewire\Component;

class ProjectList extends Component
{
    public $organization;

    public $isModalOpen = false; // Controla si el modal está abierto o cerrado

    protected $listeners = ['refreshProjectList' => '$refresh'];

    public function openModal()
    {
        $this->isModalOpen = true; // Abre el modal
    }

    public function closeModal()
    {
        $this->isModalOpen = false; // Cierra el modal
    }

    public function render()
    {
        return view('livewire.project-list', [
            'projects' => $this->organization->projects()->latest()->get(),
        ]);
    }
}
