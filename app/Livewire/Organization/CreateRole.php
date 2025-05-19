<?php

namespace App\Livewire\Organization;

use App\Models\CustomRole;
use App\Models\Organization;
use App\Models\User;
use App\Models\Permission;
use App\Models\Membership;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Helpers\PermissionsHelper;

class CreateRole extends Component
{
    public Organization $organization;
    // public array $permissions = [
    //     'canAddMembers' => false,
    //     'canRemoveMembers' => false,
    // ];
    
    // Propiedades para manejo de roles
    public $newRoleName = '';
    public $allPermissions = [];
    public $selectedPermissions = [];
    
    // Propiedades para manejo de miembros
    public $users = [];
    public $memberships = [];
    public $roles = [];
    public $ownerId;
    
    // Formulario para agregar miembros
    public $userToAdd = null;
    public $roleToAssign = null;
    
    // Estado para UI
    public $showAddMemberModal = false;
    public $showRemoveConfirmation = false;
    public $memberToRemove = null;

    public function mount(Organization $organization, 
    // array $permissions
    )
    {
        $this->organization = $organization;
        // $this->permissions = $permissions;
        $this->ownerId = $organization->ownerRelation->user_id;
        
        $this->loadData();
    }

    protected function loadData()
    {
        $this->users = User::whereNotIn('id', 
            $this->organization->members->pluck('id')->push($this->ownerId)
        )->get();
        
        $this->memberships = $this->organization->memberships()
            ->with(['user', 'customRole'])
            ->get();
            
        $this->roles = $this->organization->customRoles()
            ->where('name', '!=', 'owner')  // ← Excluir rol owner
            ->get();
        $this->allPermissions = Permission::all();
    }
    public function getCanAddMembersProperty(): bool {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canAddMembers'];
    }

    public function getCanRemoveMembersProperty(): bool {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canRemoveMembers'];
    }
    public function getCanManageRolesProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canManageRoles'];
    }
    // Creación de roles
    public function createRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255|unique:custom_roles,name,NULL,id,organization_id,'.$this->organization->id,
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,id'
        ]);

        $role = CustomRole::create([
            'name' => $this->newRoleName,
            'organization_id' => $this->organization->id,
        ]);

        if (!empty($this->selectedPermissions)) {
            $role->permissions()->sync($this->selectedPermissions);
        }

        $this->reset(['newRoleName', 'selectedPermissions']);
        $this->loadData(); // Recargar datos
        
        $this->dispatch('notify', type: 'success', message: 'Rol creado exitosamente.');
    }

    // Asignación de roles a usuarios
    public function assignRoleToUser($membershipId, $roleId)
    {
            if (!$this->canManageRoles) {
        abort(403, 'No tienes permiso para gestionar roles');
    }

    validator(['roleId' => $roleId], [
        'roleId' => 'required|exists:custom_roles,id',
    ])->validate();

        Membership::where('id', $membershipId)
            ->update(['custom_role_id' => $roleId]);

        $this->loadData();
        $this->dispatch('notify', type: 'success', message: 'Rol asignado exitosamente.');
    }

    // Agregar miembros
    public function openAddMemberModal()
    {
        $this->reset(['userToAdd', 'roleToAssign']);
        $this->showAddMemberModal = true;
    }

    public function addMember()
    {
        $this->validate([
            'userToAdd' => 'required|exists:users,id',
            'roleToAssign' => 'nullable|exists:custom_roles,id'
        ]);

        // Verificar si el usuario ya es miembro
        if ($this->organization->members()->where('user_id', $this->userToAdd)->exists()) {
            $this->dispatch('notify', type: 'error', message: 'El usuario ya es miembro de esta organización.');
            return;
        }

        // Agregar el miembro
        $this->organization->members()->attach($this->userToAdd, [
            'custom_role_id' => $this->roleToAssign
        ]);

        $this->showAddMemberModal = false;
        $this->loadData();
        $this->dispatch('notify', type: 'success', message: 'Miembro agregado exitosamente.');
    }

    // Eliminar miembros
    public function confirmRemoveMember($membershipId)
    {
        $this->memberToRemove = $membershipId;
        $this->showRemoveConfirmation = true;
    }

    public function removeMember()
    {
        $membership = Membership::findOrFail($this->memberToRemove);
        
        // Verificar que no sea el owner
        if ($membership->user_id == $this->ownerId) {
            $this->dispatch('notify', type: 'error', message: 'No puedes eliminar al dueño de la organización.');
            return;
        }

        $membership->delete();
        
        $this->showRemoveConfirmation = false;
        $this->loadData();
        $this->dispatch('notify', type: 'success', message: 'Miembro eliminado exitosamente.');
    }

    public function render()
    {
        return view('livewire.organization.create-role');
    }
}