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

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    public function rules() // ✅ Añadí validación
    {
        return [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'assignedTo' => 'required|array|min:1'
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
    if (empty($this->selectedTasks)) {
        $this->dispatch('show-toast', type: 'error', message: 'No hay tareas seleccionadas');
        return;
    }

    try {
        // Actualizar el estado de las tareas seleccionadas
        TaskUser::whereIn('id', $this->selectedTasks)
                ->update(['status' => 'pending']);
        
        // Limpiar selección
        $this->selectedTasks = [];
        
        // Emitir eventos
        $this->dispatch('show-toast', type: 'success', message: 'Solicitud enviada correctamente');
        $this->dispatch('tasks-submitted'); // Para actualizar otras partes si es necesario
    } catch (\Exception $e) {
        $this->dispatch('show-toast', type: 'error', message: 'Error al enviar solicitud: '.$e->getMessage());
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
        $tasks = $this->project->tasks()->with(['users' => function ($query) {
            $query->wherePivot('status', '!=', 'approved');
        }])->get();

        $users = User::whereHas('organizations', function ($query) {
            $query->where('organization_id', $this->project->organization_id);
        })->get();

        return view('livewire.project-tasks', compact('tasks', 'users'));
    }
}