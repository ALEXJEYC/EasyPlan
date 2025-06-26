<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use App\Models\CustomRole;
use App\Models\Permission;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CreateOrganization extends Component
{
    use WithFileUploads;

    public $isModalOpen = false;
    public $name;
    public $description;
    public $image;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->reset(['isModalOpen', 'name', 'description', 'image']);
        $this->resetErrorBag();
    }

    public function createOrganization()
    {
        $this->validate();

        try {
            $organization = Organization::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            if ($this->image) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->image->getRealPath())->cover(800, 600);
                $path = 'organizations/org-' . $organization->id . '-' . time() . '.jpg';
                Storage::disk('public')->put($path, $image->toJpeg(80));
                $organization->addMedia(storage_path('app/public/' . $path))->toMediaCollection('images');
            }

            $ownerRole = CustomRole::firstOrCreate([
                'organization_id' => $organization->id,
                'name' => 'owner',
            ]);
            $this->assignDefaultPermissionsToOwner($ownerRole);

            $membership = $organization->memberships()
                ->where('user_id', auth()->id())
                ->first();

            if ($membership) {
                $membership->custom_role_id = $ownerRole->id;
                $membership->save();
            }

            $this->closeModal(); // <-- CIERRA EL MODAL AQUÍ

            // Disparamos el evento de éxito
            $this->dispatch(
                'show-success-message',
                title: '¡Organización creada!',
                message: 'La organización se ha creado correctamente.'
            );
            $this->dispatch('organization-created');
        } catch (\Exception $e) {
            $this->dispatch(
                'show-error-message',
                message: 'Error al crear la organización: ' . $e->getMessage()
            );
        }
    }
    protected function assignDefaultPermissionsToOwner(CustomRole $ownerRole)
    {
        $allPermissions = Permission::all();
        $ownerRole->permissions()->sync($allPermissions->pluck('id')->toArray());
    }

    public function render()
    {
        return view('livewire.create-organization');
    }
}
