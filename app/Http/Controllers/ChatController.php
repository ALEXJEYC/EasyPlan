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
        $organization = $chat->organization;
        $this->authorize('view', $chat);
        $this->authorize('view', $organization);
        if (!$chat->users->contains(auth()->user())) {
        abort(403, 'No tienes permiso para acceder a este chat.');
    }
        
        // abort_unless($chat->users->contains(auth()->id()), 403);
        // $this->authorize('view', $chat);

        return view('app.chat', compact('chat'));
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
    // public function sendTypingEvent(Request $request)
    // {
    //     $user = auth()->user();
    //     $chatId = $request->chat_id; 

    //     broadcast(new Typing($user, $chatId));
    // }
}
