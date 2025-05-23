<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\TaskStatus;

class TaskStatusBadge extends Component
{
    public string $status;
    
    public function mount(string $status)
    {
        $this->status = $status;
    }

    public function render()
    {
        $color = match($this->status) {
            TaskStatus::APPROVED->value => 'green',
            TaskStatus::REJECTED->value => 'red',
            TaskStatus::SUBMITTED->value => 'blue',
            default => 'gray'
        };

        return view('livewire.task-status-badge', [
            'color' => $color,
            'label' => TaskStatus::tryFrom($this->status)?->name // Corregido aquí
        ]);
    }
}