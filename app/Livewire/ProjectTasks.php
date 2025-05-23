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
    // Depuración - verifica los datos recibidos
    \Log::debug('Datos del formulario:', [
        'title' => $this->title,
        'description' => $this->description,
        'deadline' => $this->deadline,
        'assignedTo' => $this->assignedTo,
        'project_id' => $this->project->id
    ]);

    // Valida los datos
    $this->validate();

    try {
        $task = Task::create([
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
        ]);

        // Asignar usuarios a la tarea
        $task->users()->attach($this->assignedTo);

        $this->reset(['title', 'description', 'deadline', 'assignedTo']);
        $this->dispatch('task-created');
        
        // Mensaje de éxito
        session()->flash('message', 'Tarea creada exitosamente!');
    } catch (\Exception $e) {
        \Log::error('Error al crear tarea: ' . $e->getMessage());
        session()->flash('error', 'Error al crear la tarea: ' . $e->getMessage());
    }}
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
    }
public function toggleTask($taskUserId)
{
    if (in_array($taskUserId, $this->selectedTasks)) {
        $this->selectedTasks = array_diff($this->selectedTasks, [$taskUserId]);
    } else {
        $this->selectedTasks[] = $taskUserId;
    }
}

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