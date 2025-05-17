<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use App\Models\CustomRole;
use App\Models\OrganizationOwner;

class CreateOrganization extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function createOrganization()
    {

        $this->validate();

        $organization = Organization::create([
            'name' => $this->name,
        ]);

        OrganizationOwner::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
        ]);
    // Crear o buscar el rol Fundador
        $propietario = CustomRole::firstOrCreate([
            'organization_id' => $organization->id,
            'name' => 'owner',
        ]);

        // Agregar al dueño como miembro con rol Fundador
        $organization->memberships()->create([
            'user_id' => auth()->id(),
            'custom_role_id' => $propietario->id,
        ]);
        $this->reset('name');

        session()->flash('message', 'Organización creada exitosamente.');

        $this->dispatch('organization-created', $organization->id);
    }
    public function render()
    {
        return view('livewire.create-organization');
    }
}