<?php

namespace App\Observers;

use App\Models\TaskUser;
use App\Services\Task\TaskNotificationService;

class TaskUserObserver
{
    public function updated(TaskUser $taskUser)
    {
        if ($taskUser->isDirty('status')) {
            app(TaskNotificationService::class)->notifyStatusChange($taskUser);
        }
    }
}

//✅ Sí se conecta indirectamente con Livewire o notificaciones.
// Este observer es una especie de "disparador" lógico como un hook.