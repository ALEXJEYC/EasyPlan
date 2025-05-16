<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;
use App\Models\Organization;

class ChatsGlobal extends Component
{
    public $organization;
    public $chats;

    protected $listeners = ['chatCreated' => 'loadChats'];

    public function mount($organization)
    {
        $this->organization = $organization;
        $this->loadChats();
    }

    public function loadChats()
    {
        $user = auth()->user();

        // Solo chats donde el usuario está asociado
        $this->chats = $this->organization->chats()->whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();
    }


    public function render()
    {
        return view('livewire.chats-global');
    }
}
