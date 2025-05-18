<?php

namespace App\Livewire\Organization;

use App\Models\CustomRole;
use App\Models\Organization;
use App\Models\User;
use App\Models\Permission;
use Livewire\Component;


class CreateRole extends Component
{
    public Organization $organization;
    public $users = [];
    // public $roles = [];
    public $members = [];
    public $ownerId;
    public $canManageMembers = false;

    public $user_id = '';
    public $custom_role_id = '';
    public $newRoleName = '';
    public $allPermissions = [];
    public $selectedPermissions = [];
    public $selectedUserForRole = null;
    public $showTransferModal = false;
    public $memberToRemove;

    public function openTransferModal($membershipId)
    {
        $this->memberToRemove = $membershipId;
        $this->showTransferModal = true;
    }

    public function closeTransferModal()
    {
        $this->showTransferModal = false;
        $this->memberToRemove = null;
    }

    public function mount(Organization $organization)
    {
        $this->organization = $organization;
        $this->users = User::where('id', '!=', auth()->id())->get(); // Excluir al owner
        $this->allPermissions = Permission::all();
            $this->ownerId = $organization->owner_id; // o como tengas el dato

    }

    public function createRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255',
        ]);

        $role = CustomRole::create([
            'name' => $this->newRoleName,
            'organization_id' => $this->organization->id,
        ]);

        if (!empty($this->selectedPermissions)) {
            $role->permissions()->sync($this->selectedPermissions);
        }

        $this->reset(['newRoleName', 'selectedPermissions']);
        session()->flash('message', 'Rol y permisos creados exitosamente.');
    }

    public function addMember()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'custom_role_id' => 'nullable|exists:custom_roles,id',
        ]);

        // Verificar si el usuario ya es miembro de la organización
        $exists = $this->organization->memberships()
            ->where('user_id', $this->user_id)
            ->exists();

        if ($exists) {
            session()->flash('error', 'El usuario ya es miembro de la organización.');
            return;
        }

        $this->organization->memberships()->create([
            'user_id' => $this->user_id,
            'custom_role_id' => $this->custom_role_id ?: null,
        ]);

        session()->flash('message', 'Miembro agregado exitosamente.');
        $this->reset(['user_id', 'custom_role_id']);
    }

    public function removeMember($membershipId)
    {
        $membership = $this->organization->memberships()->findOrFail($membershipId);

        // Verifica si es el owner
        $isOwner = $this->organization->owner->user_id === $membership->user_id;

        if ($isOwner) {
            session()->flash('error', 'No puedes eliminar al propietario de la organización.');
            return;
        }

        $membership->delete();
        session()->flash('message', 'Miembro eliminado.');
    }

    public function assignRoleToUser()
    {
        $this->validate([
            'selectedUserForRole' => 'required|exists:users,id',
            'custom_role_id' => 'required|exists:custom_roles,id',
        ]);

        $this->organization->memberships()
            ->where('user_id', $this->selectedUserForRole)
            ->update([
                'custom_role_id' => $this->custom_role_id,
            ]);

        $this->reset(['selectedUserForRole', 'custom_role_id']);
        session()->flash('message', 'Rol asignado exitosamente.');
    }


    public function render()
    {
        // $this->users = User::where('id', '!=', auth()->id())->get();
        $user = auth()->user();
        $canCreateRoles = $user->hasPermissionInOrganization('create_roles', $this->organization->id);
        $canAddUsers = $user->hasPermissionInOrganization('add_users', $this->organization->id);


        return view('livewire.organization.create-role', [
            'memberships' => $this->organization->memberships()->with(['user', 'customRole'])->get(),
            'roles' => $this->organization->customRoles,
            'users' => $this->users,
            'allPermissions' => $this->allPermissions,
            'canCreateRoles' => $canCreateRoles,
            'canAddUsers' => $canAddUsers,
            'canManageMembers' => $this->canManageMembers,
            // 'members' => $this->organization->memberships()->with('user', 'customRole')->get(),
        ]);
    }



}