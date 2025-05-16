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
        return view('organizations.show', compact('organization', 'users', 'chats'));
    }
    public function addMember(Request $request, Organization $organization)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string',
        ]);
    
        // Agregar el usuario a la organización con el rol especificado
        $organization->members()->attach($request->user_id, ['role' => $request->role]);
    
        // Agregar el usuario al chat grupal de la organización
        $chat = $organization->chats()->where('type', 'group')->first();
        if ($chat) {
            $chat->users()->syncWithoutDetaching($request->user_id); // Agregar sin eliminar los existentes
        }
    
        return redirect()->back()->with('success', 'Usuario agregado a la organización con éxito.');
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
}
