<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectTasks extends Component
{
    public $project;
    public $title;
    public $description;
    public $deadline;
    public $assignedTo = [];
    public $selectedTasks = [];
    public $observation = '';
    protected $listeners = ['tasksUpdated' => 'render'];

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after_or_equal:today', 
            'assignedTo' => 'required|array|min:1',
            'observation' => 'nullable|string|max:500'
        ];
    }
public function createTask()
{
    $this->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'required|date',
        'assignedTo' => 'required|array|min:1',
    ]);

    $task = Task::create([
        'project_id' => $this->project->id, // Reemplaza con el ID del proyecto
        'title' => $this->title,
        'description' => $this->description,
        'deadline' => $this->deadline,
        'created_by' => Auth::id(), // asegúrate de tener este campo
    ]);

    $task->users()->attach($this->assignedTo);



    // Limpia el formulario
    $this->reset(['title', 'description', 'deadline', 'assignedTo']);
    // $this->dispatch('tasksUpdated'); //emisor de evento


    session()->flash('message', 'Tarea creada exitosamente.');
}
    // Agrega este método a tu componente Livewire
    public function submitSelectedTasks()
    {
        $this->validate(['observation' => 'nullable|string|max:500']);
        
        try {
            TaskUser::whereIn('id', $this->selectedTasks)
                ->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                    'observation' => $this->observation,
                ]);
            
            $this->selectedTasks = [];
            $this->observation = '';
            
            $this->dispatch('notify', 
                type: 'success', 
                message: 'Solicitudes enviadas para revisión'
            );
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error', 
                message: 'Error: '.$e->getMessage()
            );
        }
    }
    public function approveTask()
    {
        $taskUser = TaskUser::find($this->taskUserId);
        $taskUser->update(['status' => 'approved']);
        $this->dispatch('tasksUpdated'); 
        //emisor 

    }
    public function toggleTask($taskUserId)
    {
        if (in_array($taskUserId, $this->selectedTasks)) {
            $this->selectedTasks = array_diff($this->selectedTasks, [$taskUserId]);
        } else {
            $this->selectedTasks[] = $taskUserId;
        }
    }
    public function canResubmit($taskUser)
{
    return $taskUser->status === TaskStatus::REJECTED->value && $taskUser->user_id === Auth::id();
}

// public function resubmitTask($taskUserId)
// {
//     $taskUser = TaskUser::findOrFail($taskUserId);
    
//     if ($taskUser->user_id !== Auth::id()) {
//         abort(403);
//     }

//     $taskUser->update([
//         'status' => TaskStatus::SUBMITTED->value,
//         'submitted_at' => now(),
//     ]);

//     $this->dispatch('notify', type: 'success', message: 'Tarea reenviada');
//     $this->dispatch('tasksUpdated');
// }


    public function render()
    {
        $tasks = $this->project->tasks()->with(['users' => function($query) {
            $query->whereNotIn('status', ['approved', 'submitted']);
        }])->get();

        $users = User::whereHas('organizations', function ($query) {
            $query->where('organization_id', $this->project->organization_id);
        })->get();

        return view('livewire.project-tasks', compact('tasks', 'users'));
    }

}