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

        // Subir la imagen si se proporciona
        if ($this->image) {
        // Mover el archivo temporal a una ubicación permanente
        $path = $this->image->store('projects/images', 'public');

        // Asociar el archivo al proyecto usando Spatie Media Library
        $project->addMedia(storage_path('app/public/' . $path))->toMediaCollection('images');
        }

        // Asociar el proyecto con los miembros seleccionados
        $this->members = array_filter($this->members, function ($member) {
            return $member !== auth()->id();
        });
        $this->members[] = auth()->id(); // Asegurarse de que el creador esté incluido
        $this->members = array_unique($this->members); // Eliminar duplicados
        $project->users()->sync($this->members); // Sincronizar los miembros seleccionados


        // Crear un chat para el proyecto
        Chat::create([  
            'type' => 'project',
            'name' => $this->name . ' Chat',
            'project_id' => $project->id,
            'organization_id' => $this->organization->id,
            'created_by' => auth()->id(),
        ]);
        // Emitir un evento para actualizar la lista de proyectos
        $this->dispatch('refreshProjectList');

        // Reiniciar los campos y cerrar el modal
        $this->reset(['name', 'description', 'image', 'members']);
    }
    public function render()
    {
        return view('livewire.create-project', [
            'organizationMembers' => $this->organization->members,
        ]);
    }
}
