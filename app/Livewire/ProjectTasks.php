<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\TaskUser;
use App\Models\Task;
use App\Models\TaskEvidence;
use App\Models\User;
use App\Enums\TaskStatus;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\PermissionsHelper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class ProjectTasks extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $evidenceFiles = []; // Para almacenar los archivos subidos
    public $uploadedEvidences = []; // Para mostrar los archivos ya subidos

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
    //agregamos funcion de permiso para crear tareas
    public function getCanCreateTasksProperty(): bool
    {
        return PermissionsHelper::getFor(auth()->user(), $this->project->organization)['canCreateTasks'];
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


        $asignados = count($this->assignedTo);


        $this->reset(['title', 'description', 'deadline', 'assignedTo']);

        $this->dispatch('tasksUpdated');
        $this->dispatch('taskCreated', projectId: $this->project->id);


        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Tarea creada con ' . $asignados . ' asignados'
        );
    }
    public function submitTask()
    {
        $this->validate([
            'observation' => 'nullable|string|max:500',
            'evidenceFiles.*' => 'nullable|file|max:10240', // 10MB máximo por archivo
        ]);

        try {
            $task = Task::findOrFail($this->selectedTaskId);

            if (!$task->users()->where('user_id', auth()->id())->exists()) {
                throw new \Exception('No estás asignado a esta tarea');
            }

            $taskStatus = $task->status;

            if ($taskStatus instanceof TaskStatus) {
                $taskStatus = $taskStatus->value;
            }

            if ($taskStatus !== TaskStatus::PENDING->value && $taskStatus !== TaskStatus::REJECTED->value) {
                throw new \Exception('Esta tarea no puede ser enviada para revisión');
            }

            DB::transaction(function () use ($task) {
                // Actualizar estado de la tarea
                $task->update([
                    'status' => TaskStatus::SUBMITTED->value,
                    'submitted_at' => now(),
                    'submitted_by' => auth()->id(),
                    'observation' => $this->observation,
                ]);

                // Obtener la relación TaskUser
                $taskUser = TaskUser::where('task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->first();

                // Guardar archivos de evidencia
                if ($this->evidenceFiles) {
                    foreach ($this->evidenceFiles as $file) {
                        $originalName = $file->getClientOriginalName();
                        $path = $file->store('task_evidences', 'public');

                        TaskEvidence::create([
                            'task_user_id' => $taskUser->id,
                            'file_path' => $path,
                            'file_name' => $originalName,
                            'file_size' => $file->getSize(),
                            'file_type' => $file->getMimeType(),
                        ]);
                    }
                }

                $task->users()->updateExistingPivot(auth()->id(), [
                    'status' => TaskStatus::PENDING->value,
                ]);
            });

            $this->reset(['observation', 'selectedTaskId', 'showSubmissionModal', 'evidenceFiles']);
            $this->dispatch('tasksUpdated');
            $this->dispatch('taskSubmitted', projectId: $this->project->id);
            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Tarea enviada para revisión con evidencia adjunta'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: $e->getMessage()
            );
        }
    }
    public function removeEvidence($index)
    {
        if (isset($this->evidenceFiles[$index])) {
            unset($this->evidenceFiles[$index]);
            $this->evidenceFiles = array_values($this->evidenceFiles); // Reindexar array
        }
    }
    public function removeUploadedEvidence($evidenceId)
    {
        try {
            $evidence = TaskEvidence::findOrFail($evidenceId);

            // Verificar que la evidencia pertenece al usuario actual
            if ($evidence->taskUser->user_id !== auth()->id()) {
                throw new \Exception('No tienes permiso para eliminar esta evidencia');
            }

            // Eliminar archivo físico
            Storage::disk('public')->delete($evidence->file_path);

            // Eliminar registro de la base de datos
            $evidence->delete();

            // Actualizar lista de evidencias subidas
            $this->uploadedEvidences = $evidence->taskUser->evidences()->get()->toArray();

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Evidencia eliminada correctamente'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
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
        $this->evidenceFiles = [];
        $this->uploadedEvidences = [];

        // Cargar evidencias existentes si las hay
        $taskUser = TaskUser::where('task_id', $taskId)
            ->where('user_id', auth()->id())
            ->first();

        if ($taskUser) {
            $this->uploadedEvidences = $taskUser->evidences()->get()->toArray();
        }
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
