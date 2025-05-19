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
