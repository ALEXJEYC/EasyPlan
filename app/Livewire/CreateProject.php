<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Project, Chat};
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CreateProject extends Component
{
    use WithFileUploads;

    public $organization;
    
    #[Rule('required|string|max:255')]
    public $name;
    
    #[Rule('nullable|string')]
    public $description;
    
    #[Rule('nullable|image|mimes:jpeg,png,jpg,gif|max:1024')]
    public $image;
    
    #[Rule('required|array|min:1')]
    public $members = [];

    public function mount($organization)
    {
        $this->organization = $organization;
    }

    public function createProject()
    {
        $this->validate();
        
        $project = $this->organization->projects()->create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => 'iniciado',
        ]);

        // Procesar imagen si existe
        if ($this->image) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($this->image->getRealPath())->cover(800, 600);
            $path = 'projects/project-'.$project->id.'-'.time().'.jpg';
            Storage::disk('public')->put($path, $image->toJpeg(80));
            $project->addMedia(storage_path('app/public/'.$path))->toMediaCollection('images');
        }

        // Asignar miembros (incluyendo al creador)
        $members = collect($this->members)
            ->push(auth()->id())
            ->unique()
            ->toArray();
        
        $project->users()->sync($members);

        // Crear chat asociado
        Chat::create([
            'type' => 'project',
            'name' => $this->name.' Chat',
            'project_id' => $project->id,
            'organization_id' => $this->organization->id,
            'created_by' => auth()->id(),
        ])->users()->sync($members);

        // Emitir eventos
        $this->dispatch('projectCreated', projectId: $project->id);
        $this->reset(['name', 'description', 'image', 'members']);
    }

    public function getOrganizationMembersProperty()
    {
        return $this->organization->members()
            ->where('users.id', '!=', auth()->id())
            ->get();
    }

    public function render()
    {
        return view('livewire.create-project');
    }
}