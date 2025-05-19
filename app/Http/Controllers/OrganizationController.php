<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Helpers\PermissionsHelper;

class OrganizationController extends Controller
{
    public function show(Organization $organization)
    {
        $this->authorize('view', $organization);

        $chats = $organization->chats()->whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })->get();

        $users = User::whereNotIn('id', $organization->members->pluck('id'))->get();
        $user = auth()->user();
        $permissions = PermissionsHelper::getFor($user, $organization);
        $organization->load('members', 'projects', 'chats');

        return view('organizations.show', [
            'organization' => $organization,
            'users' => $users,
            'chats' => $chats,
             ...$permissions,
        ]);
    }
    public function syncChatMembers(Organization $organization)
    {
        $chat = $organization->chats()->where('type', 'group')->first();
    
        if ($chat) {
            // Sincronizar todos los miembros de la organización con el chat grupal
            $chat->users()->sync($organization->members->pluck('id'));
        }
    
        return redirect()->back()->with('success', 'Miembros sincronizados con el chat grupal.');
    }
    

    public function updateMemberRole(Request $request, Organization $organization, User $user)
    {
        $this->authorize('manageMembers', $organization);

        $request->validate([
            'role' => 'required|string',
        ]);

        $organization->members()->updateExistingPivot($user->id, [
            'role' => $request->role
        ]);

        return back()->with('success', 'Rol actualizado.');
    }
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->delete();

        return redirect()->route('dashboard')->with('success', 'Organización eliminada.');
    }



    
}
