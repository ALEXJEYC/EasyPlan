<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Organization;

class PermissionsHelper
{
    public static function getFor(User $user, Organization $organization): array
    {
        return [
            'canAddMembers' => $user->hasPermissionInOrganization('add_members', $organization->id),
            'canRemoveMembers' => $user->hasPermissionInOrganization('remove_members', $organization->id),
            'canManageRoles' => $user->hasPermissionInOrganization('manage_roles', $organization->id),
            'canCreateProject' => $user->hasPermissionInOrganization('create_project', $organization->id),
            'canreviewTasks' => $user->hasPermissionInOrganization('Review_tasks', $organization->id),
            'canCreateTasks' => $user->hasPermissionInOrganization('create_tasks', $organization->id),
            'canCreateRoles' => $user->hasPermissionInOrganization('create_roles', $organization->id),
            //no se incluye el permiso para transferir la organización
            // 'canTransferOrganization' => $user->hasPermissionInOrganization('can_transfer_organization', $organization->id),
            'canManageProjects' => $user->hasPermissionInOrganization('Manage_projects', $organization->id),
        ];
    }
    public static function userHasPermission(User $user, Organization $org, string $permission): bool {
        // 1. Si es owner, tiene todos los permisos
        if ($org->isOwner($user)) {
            return true;
        }

        // 2. Verificar permisos del rol asignado
        $membership = $org->members()->where('user_id', $user->id)->first();
        if ($membership && $membership->customRole) {
            return $membership->customRole->permissions->contains('name', $permission);
        }

        return false;
    }
}
