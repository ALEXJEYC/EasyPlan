<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use App\Models\CustomRole;
use App\Models\Permission;
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
        $ownerRole = CustomRole::firstOrCreate([
            'organization_id' => $organization->id,
            'name' => 'owner',
        ]);
        // Asignar permisos por defecto al rol 'owner'
         // (Puedes definir esta función en CustomRole o aquí mismo)
         $this->assignDefaultPermissionsToOwner($ownerRole);

        // Agregar al dueño como miembro con rol Fundador
        $organization->memberships()->create([
            'user_id' => auth()->id(),
            'custom_role_id' => $ownerRole->id,
        ]);
        $this->reset('name');

        session()->flash('message', 'Organización creada exitosamente.');

        $this->dispatch('organization-created', $organization->id);
    }
    protected function assignDefaultPermissionsToOwner(CustomRole $ownerRole)
    {
        // Obtener todos los permisos que existen
        $allPermissions = Permission::all();

        // Asignar permisos al rol owner (sync para evitar duplicados)
        $ownerRole->permissions()->sync($allPermissions->pluck('id')->toArray());
    }

    
    public function render()
    {
        return view('livewire.create-organization');
    }
}