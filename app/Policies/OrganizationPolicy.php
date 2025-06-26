<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function manageMembers(User $user, Organization $organization)
    // {
    //     return $organization->members->contains($user) &&
    //         $user->hasPermissionTo('agregar_usuarios');
    // }

    public function delete(User $user, Organization $organization)
    {
        return $organization->members->contains($user) &&
            $user->hasPermissionTo('eliminar_organizacion');
    }
    // public function manageMembers(User $user, Organization $organization)
    // {
    //     return $organization->members->contains($user) &&
    //         $user->hasPermissionInOrganization('agregar_usuarios', $organization->id);
    // }
    public function manageAddMembers(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user) || 
            $user->hasPermissionInOrganization('add_members', $organization->id);
    }

    public function manageRemoveMembers(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user) || 
            $user->hasPermissionInOrganization('remove_members', $organization->id);
    }
    public function manageRoles(User $user, Organization $org): bool
    {
        return $org->isOwner($user) || 
            $user->hasPermissionInOrganization('manage_roles', $org->id);
    }
    public function manageCreateProject(User $user, Organization $org): bool
    {
        return $org->isOwner($user) || 
            $user->hasPermissionInOrganization('create_project', $org->id);
    }
    public function manageReviewTasks(User $user, Organization $org): bool
    {
        return $org->isOwner($user) || 
            $user->hasPermissionInOrganization('Review_tasks', $org->id);
    }




    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        // Check if the user is a member of the organization
        if ($organization->members->contains($user)) {
            return true;
        }

        // Check if the user is the owner of the organization
        if ($organization->owner_id === $user->id) {
            return true;
        }

        // If neither, deny access
        return false;
        // return $user->id === $organization->owner_id
        //     ? Response::allow()
        
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    // public function delete(User $user, Organization $organization): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    public function restore(User $user, Organization $organization): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        //
    }
}
