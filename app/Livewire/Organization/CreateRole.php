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
    public $memberToRemove = null;
    //trasnferir organización
    public $showTransferModal = false;
    public $transferToUserId = null;
    //cambiar rol de miembro
    public $pendingRoleChange = null;
    //nombres de las propiedades para permisos
    public $permissionsnames = [
        'add_members' => 'Agregar miembros',
        'remove_members' => 'Eliminar miembros',
        'manage_roles' => 'Gestionar roles',
        'create_project' => 'Crear proyecto',
        'Review_tasks' => 'Revisar tareas',
        'create_tasks' => 'Crear tareas',
        'create_roles' => 'Crear roles',
        'Manage_projects' => 'Gestionar proyectos',

    ];

    public function openTransferModal()
    {
        $this->showTransferModal = true;
        $this->transferToUserId = null;
    }
    //funcion para transferir organización


    public function transferOrganization()
    {
        $this->validate([
            'transferToUserId' => 'required|exists:users,id|different:' . $this->organization->ownerRelation->user_id,
        ]);

        // Disparar SweetAlert de confirmación
        $this->dispatch('confirm-transfer');
    }

    // Este método se llama si el usuario confirma la transferencia
    public function confirmTransferOrganization($remove = false)
    {
        $oldOwnerId = $this->organization->ownerRelation->user_id;
        $newOwnerId = $this->transferToUserId;

        // Cambiar owner
        $this->organization->ownerRelation->update(['user_id' => $newOwnerId]);

        // Si el owner decide retirarse, lo eliminamos de la organización
        if ($remove) {
            $this->organization->members()->detach($oldOwnerId);
        } else {
            // Si decide quedarse, lo dejamos como miembro sin rol
            $this->organization->members()->updateExistingPivot($oldOwnerId, ['custom_role_id' => null]);
        }

        $this->showTransferModal = false;
        $this->loadData();

        $this->dispatch('notify', type: 'success', message: 'Organización transferida exitosamente.');
    }


    public function showConfirm()
    {
        $this->validate();

        // Disparamos el evento de confirmación
        $this->dispatch('show-confirm-dialog');
    }

    public function mount(
        Organization $organization,
        // array $permissions
    ) {
        $this->organization = $organization;
        // $this->permissions = $permissions;
        $this->ownerId = $organization->ownerRelation->user_id;

        $this->loadData();
    }

    protected function loadData()
    {
        $this->users = User::whereNotIn(
            'id',
            $this->organization->members->pluck('id')->push($this->ownerId)
        )->get();

        $this->memberships = $this->organization->memberships()
            ->with(['user', 'customRole'])
            ->get();

        $this->roles = $this->organization->customRoles()
            ->where('name', '!=', 'owner')  // ← Excluir rol owner
            ->get();
        $this->allPermissions = Permission::where('name', '!=', 'can_transfer_organization')->get();
    }
    //funcion para agregar roles
    public function getCanCreateRolesProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canCreateRoles'];
    }
    public function getCanAddMembersProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canAddMembers'];
    }

    public function getCanRemoveMembersProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canRemoveMembers'];
    }
    public function getCanManageRolesProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->organization)['canManageRoles'];
    }
    // Creación de roles
    public function createRole()
    {
        // Emitir evento para mostrar la alerta de confirmación
        $this->dispatch('confirm-rol');
    }
    public function createRoleConfirmed()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255|unique:custom_roles,name,NULL,id,organization_id,' . $this->organization->id,
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

        // Disparar evento de éxito
        $this->dispatch(
            'show-success-message',
            title: '¡Rol creado!',
            message: 'El rol se ha creado correctamente.'
        );

        $this->dispatch('notify', type: 'success', message: 'Rol creado exitosamente.');
    }
    public function confirmChangeRole($membershipId, $roleId)
    {
        $this->pendingRoleChange = ['membershipId' => $membershipId, 'roleId' => $roleId];
        $this->dispatch('show-role-change-confirmation');
    }

    public function applyRoleChange()
    {
        if ($this->pendingRoleChange) {
            $this->assignRoleToUser(
                $this->pendingRoleChange['membershipId'],
                $this->pendingRoleChange['roleId']
            );
            $this->pendingRoleChange = null;
        }
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

        // Agregar el miembro a la organización
        $this->organization->members()->attach($this->userToAdd, [
            'custom_role_id' => $this->roleToAssign
        ]);
        $this->dispatch(
            'show-success-message',
            title: 'Usuario agregado',
            message: 'El usuario ha sido agregado a la organización correctamente.'
        );

        //  También agregarlo al chat grupal de la organización
        $chat = $this->organization->chats()->where('type', 'group')->first();

        if ($chat) {
            $chat->users()->syncWithoutDetaching([$this->userToAdd]);
        }

        $this->showAddMemberModal = false;
        $this->loadData();

        $this->dispatch('notify', type: 'success', message: 'Miembro agregado exitosamente al chat y a la organización.');
    }

    // Eliminar miembros
    public function confirmRemoveMember($membershipId)
    {
        $this->memberToRemove = $membershipId;
        $this->dispatch('show-remove-confirmation');
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

        $this->loadData();
        $this->dispatch('notify', type: 'success', message: 'Miembro eliminado exitosamente.');
    }

    public function render()
    {
        return view('livewire.organization.create-role');
    }
}
