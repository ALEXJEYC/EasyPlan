<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatus;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectTasks extends Component
{
    use WithPagination;
    
    public $project;
    public $title;
    public $description;
    public $deadline;
    public $assignedTo = [];
    public $observation = '';
    public $globalSearch = '';
    public $searchAssignedUser = '';
    public $searchDeadline = '';
    public $statusFilter = '';
    public $showSubmissionModal = false;
    public $selectedTaskId;
    public $selectedTask;

    protected $listeners = [
        'tasksUpdated' => 'render',
        'taskCreated' => 'render',
        'taskSubmitted' => 'render',
        'taskApproved' => 'render',
        'taskRejected' => 'render',
        'refresh' => '$refresh'
    ];

    protected $paginationTheme = 'tailwind';

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

    public function clearFilters()
    {
        $this->reset([
            'globalSearch', 
            'searchAssignedUser', 
            'searchDeadline', 
            'statusFilter'
        ]);
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, [
            'globalSearch',
            'searchAssignedUser',
            'searchDeadline',
            'statusFilter'
        ])) {
            $this->resetPage();
        }
    }

    // public function mount(Project $project)
    // {
    //     $this->project = $project;
    // }
    // public function rules()
    // {
    //     return [
    //         'title' => 'required|min:3',
    //         'description' => 'nullable|string',
    //         'deadline' => 'required|date|after_or_equal:today', 
    //         'assignedTo' => 'required|array|min:1',
    //         'observation' => 'nullable|string|max:500'
    //     ];
    // }
    public function createTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'assignedTo' => 'required|array|min:1',
        ]);

        // Crear la tarea principal
        $task = Task::create([
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'created_by' => Auth::id(),
            'status' => TaskStatus::PENDING->value,
        ]);

        // Crear registros en task_user para cada usuario asignado
        foreach ($this->assignedTo as $userId) {
            TaskUser::create([
                'task_id' => $task->id,
                'user_id' => $userId,
                'status' => TaskStatus::PENDING->value,
            ]);
        }

        // Limpiar el formulario y emitir eventos
        $this->reset(['title', 'description', 'deadline', 'assignedTo']);
        $this->dispatch('tasksUpdated'); // Forzar actualización

        $this->dispatch('taskCreated', projectId: $this->project->id);
        $this->dispatch('notify', 
            type: 'success', 
            message: 'Tarea creada con ' . count($this->assignedTo) . ' asignados'
        );
    }
    // Agrega este método a tu componente Livewire
public function submitTask()
{
    $this->validate(['observation' => 'nullable|string|max:500']);
    
    try {
        $task = Task::findOrFail($this->selectedTaskId);
        
        if (!$task->users()->where('user_id', auth()->id())->exists()) {
            throw new \Exception('No estás asignado a esta tarea');
        }

        $taskStatus = $task->status;

        // Si es string, asegúrate de compararlo con el valor correcto
        if ($taskStatus instanceof TaskStatus) {
            $taskStatus = $taskStatus->value;
        }

        // Permitir el envío si la tarea está pendiente o rechazada
        if ($taskStatus !== TaskStatus::PENDING->value && $taskStatus !== TaskStatus::REJECTED->value) {
            throw new \Exception('Esta tarea no puede ser enviada para revisión');
        }

        DB::transaction(function () use ($task) {
            $task->update([
                'status' => TaskStatus::SUBMITTED->value,
                'submitted_at' => now(),
                'submitted_by' => auth()->id(),
                'observation' => $this->observation,
            ]);
        });
            $task->users()->updateExistingPivot(auth()->id(), [
            'status' => TaskStatus::PENDING->value,
        ]);
    

        $this->reset(['observation', 'selectedTaskId', 'showSubmissionModal']);
        $this->dispatch('tasksUpdated');
         $this->dispatch('taskSubmitted', projectId: $this->project->id);
        $this->dispatch('notify', 
            type: 'success', 
            message: 'Tarea enviada para revisión'
        );

    } catch (\Exception $e) {
        $this->dispatch('notify', 
            type: 'error', 
            message: $e->getMessage()
        );
    }
}

    public function approveTask()
    {
        $taskUser = TaskUser::find($this->taskUserId);
        $taskUser->update(['status' => 'approved']);
        $this->dispatch('tasksUpdated'); 
        $this->dispatch('taskApproved');
        //emisor 

    }
    // public function toggleTask($taskUserId)
    // {
    //     if (in_array($taskUserId, $this->selectedTasks)) {
    //         $this->selectedTasks = array_diff($this->selectedTasks, [$taskUserId]);
    //     } else {
    //         $this->selectedTasks[] = $taskUserId;
    //     }
    // }
    public function canResubmit($taskUser)
{
    return $taskUser->status === TaskStatus::REJECTED->value && $taskUser->user_id === Auth::id();
}

public function prepareSubmit($taskId)
{
    $this->selectedTaskId = $taskId;
     $this->selectedTask = Task::find($taskId); 
    $this->showSubmissionModal = true;
}

    public function render()
    {
        $tasksQuery = $this->project->tasks()
            ->with(['users', 'submittedBy'])
            ->whereIn('status', [TaskStatus::PENDING->value, TaskStatus::REJECTED->value]);

        // Búsqueda global
        if (!empty($this->globalSearch)) {
            $searchTerm = '%' . $this->globalSearch . '%';
            $tasksQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhereHas('users', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    });
            });
        }

        // Filtro por estado
        if (!empty($this->statusFilter)) {
            $tasksQuery->where('status', $this->statusFilter);
        }

        // Filtro por fecha
        if (!empty($this->searchDeadline)) {
            $tasksQuery->whereDate('deadline', $this->searchDeadline);
        }

        // Filtro por usuario asignado
        if (!empty($this->searchAssignedUser)) {
            $tasksQuery->whereHas('users', function ($query) {
                $query->where('user_id', $this->searchAssignedUser);
            });
        }

        $users = $this->project->members()->get();
        $tasks = $tasksQuery->latest()->paginate(5);

        return view('livewire.project-tasks', compact('tasks', 'users'));
    }
}