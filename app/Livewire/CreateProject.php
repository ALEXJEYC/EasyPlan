<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Chat;

class CreateProject extends Component
{
    use WithFileUploads;

    public $organization;
    public $name;
    public $description;
    public $image;
    public $members = [];

    public $organizationMembers; // << Para pasar al render

    public function mount($organization)
    {
        $this->organization = $organization;

        // Excluir al usuario autenticado de la lista de miembros disponibles
        $this->organizationMembers = $organization->members->filter(function ($member) {
            return $member->id !== auth()->id();
        });
    }

    public function createProject()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:1024',
            'members' => 'required|array|min:1',
        ]);

        $project = $this->organization->projects()->create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => 'iniciado',
        ]);

        if ($this->image) {
            $path = $this->image->store('projects/images', 'public');
            $project->addMedia(storage_path('app/public/' . $path))->toMediaCollection('images');
        }

        // Asegurar que el creador esté incluido
        $members = collect($this->members)
            ->filter(fn($id) => $id != auth()->id())
            ->push(auth()->id())
            ->unique()
            ->toArray();

        $project->users()->sync($members);

        // Crear un chat y asociar miembros
        Chat::create([
            'type' => 'project',
            'name' => $this->name . ' Chat',
            'project_id' => $project->id,
            'organization_id' => $this->organization->id,
            'created_by' => auth()->id(),
        ])->users()->sync($members);

        // $this->dispatch('refreshProjectList');
        $this->dispatch('projectCreated');	
        $this->dispatch('chatCreated');
            $this->dispatch('projectCreated', $project->id);


        $this->reset(['name', 'description', 'image', 'members']);
    }

    public function render()
    {
        return view('livewire.create-project', [
            'organizationMembers' => $this->organizationMembers,
        ]);
    }
}
