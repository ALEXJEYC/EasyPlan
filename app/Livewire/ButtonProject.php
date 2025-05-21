<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
// use App\Models\{Project, Chat};
use Livewire\WithFileUploads;
// use Livewire\Attributes\Rule;
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;
// use Illuminate\Support\Facades\Storage;

class ButtonProject extends Component
{
    
    use WithFileUploads;

    public $isModalOpen = false;
    public $organization;

    public $name;
    public $description;
    public $image;
    public $members = [];

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'members' => 'array|min:1',
        'members.*' => 'exists:users,id',
    ];

    public function getOrganizationMembersProperty()
    {
        return $this->organization->members()
            ->where('users.id', '!=', auth()->id())
            ->get();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->reset(['isModalOpen', 'name', 'description', 'image', 'members']);
    }

    public function createProject()
    {
        $this->validate();

        $project = $this->organization->projects()->create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => 'iniciado',
        ]);

        if ($this->image) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($this->image->getRealPath())->cover(800, 600);
            $path = 'projects/project-' . $project->id . '-' . time() . '.jpg';
            Storage::disk('public')->put($path, $image->toJpeg(80));
            $project->addMedia(storage_path('app/public/' . $path))->toMediaCollection('images');
        }

        $members = collect($this->members)
            ->push(auth()->id())
            ->unique()
            ->toArray();

        $project->users()->sync($members);

        Chat::create([
            'type' => 'project',
            'name' => $this->name . ' Chat',
            'project_id' => $project->id,
            'organization_id' => $this->organization->id,
            'created_by' => auth()->id(),
        ])->users()->sync($members);

        $this->dispatch('projectCreated', projectId: $project->id);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.button-project');
    }
}
