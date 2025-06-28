<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ChatController extends Controller
{
    public function show(Chat $chat)
    {
        // Autorizar el acceso al chat
        $this->authorize('view', $chat);

        // Retornar la vista del chat
        return view('app.chat', compact('chat'));
    }
    public function index(Organization $organization)
    {
        // Obtener solo los chats de la organización y del usuario autenticado
        $chats = Chat::where('organization_id', $organization->id)
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        // También necesitas pasar los usuarios para la pestaña de "miembros"
        $users = User::whereNotIn('id', $organization->members->pluck('id'))->get();

        return view('app.chats', compact('organization', 'chats', 'users'));
    }
    

    public function getUser(Chat $chat)
    {
        $users = $chat->users;

        return response()->json([
            'users' => $users
        ]);
    }

    public function chatWith(User $user)
    {
        $user_A = auth()->user();
        $user_B = $user;

        $chat = $user_A->chats()
            ->whereHas('users', fn ($query) => $query->where('chat_user.user_id', $user_B->id))
            ->first();

        if (!$chat) {
            $chat = Chat::create([]);
            $chat->users()->sync([
                $user_A->id,
                $user_B->id
            ]);
        }

        return redirect()->route('chat.show', $chat);
    }
    public function userChats()
{
    $user = auth()->user();
    $chats = $user->chats()->with(['users', 'messages' => function($query) {
        $query->latest()->limit(1);
    }])->get();
    
    return view('chats.index', compact('chats'));
}

}
