<?php

namespace App\Livewire;

// namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectChat extends Component
{
    public Project $project;
    public $chat;

public function mount(Project $project)
{
    $this->project = $project;
    $this->chat = $project->chat; 
    // dd($this->chat);
}

public function render()
{
    return view('livewire.project-chat');
}
// $this->chat = $this->project->chats()->where('type', 'project')->first();
// debe devolver el chat asociado
}
