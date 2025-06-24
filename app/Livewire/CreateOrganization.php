<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use App\Models\CustomRole;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

// <!-- TODO: cambiar formulario de FORMULARIO  AGREGAR CAMPOS DE SUBIR IMAGEN DE ORGANIZAICON  -->
class CreateOrganization extends Component
{
    public $name;
    

    protected $rules = [
        'name' => 'required|string|max:255',
    ];
    
    public function showConfirm()
    {
        $this->validate();
        
        // Disparamos el evento de confirmación
        $this->dispatch('show-confirm-dialog');
    }
    
    // Nuevo método para manejar la creación después de confirmar
    public function doCreateOrganization()
    {
        try {
            $organization = Organization::create([
                'name' => $this->name,
            ]);
    
            // Resto de tu lógica de creación...
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
    
            $this->reset('name');
            
            // Disparamos el evento de éxito
            $this->dispatch('show-success-message', 
                title: '¡Organización creada!',
                message: 'La organización se ha creado correctamente.'
            );
            // Después de crear la organización:
            $this->dispatch('organization-created');
    
        } catch (\Exception $e) {
            $this->dispatch('show-error-message', 
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
