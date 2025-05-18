<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;

class OrganizationController extends Controller
{
    public function show(Organization $organization)
    {
        
        $this->authorize('view', $organization); // Si usas policies
            // Cargar la relación members
        $chats = $organization->chats()->whereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })->get();            
        $users = User::whereNotIn('id', $organization->members->pluck('id'))->get();
        $organization->load('members', 'projects', 'chats');
    //     return view('organizations.show', compact('organization', 'users', 'chats'));
    // }
    return view('organizations.show',compact('organization', 'users', 'chats'), [
    'organization' => $organization,
    'canManageMembers' => auth()->user()->hasPermissionInOrganization('agregar_usuarios', $organization->id),
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
    
    public function addMember(Request $request, Organization $organization)
    {
        $this->authorize('manageMembers', $organization); // Usa policies o revisa permisos manualmente

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string',
        ]);

        $organization->members()->attach($request->user_id, ['role' => $request->role]);

        $chat = $organization->chats()->where('type', 'group')->first();
        if ($chat) {
            $chat->users()->syncWithoutDetaching($request->user_id);
        }

        return back()->with('success', 'Usuario agregado con éxito.');
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
    public function removeMember(Organization $organization, User $user)
    {
        $this->authorize('manageMembers', $organization);

        $organization->members()->detach($user->id);

        $chat = $organization->chats()->where('type', 'group')->first();
        if ($chat) {
            $chat->users()->detach($user->id);
        }

        return back()->with('success', 'Usuario eliminado con éxito.');
    }
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->delete();

        return redirect()->route('dashboard')->with('success', 'Organización eliminada.');
    }



    
}
