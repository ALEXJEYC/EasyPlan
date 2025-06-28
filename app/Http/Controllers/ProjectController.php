<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $task = $project->tasks()->first() ?? new Task();

        // Cargar proyecto junto con su chat del tipo 'project'
        $project->load(['chats' => function ($query) {
            $query->where('type', 'project');
        }]);

        // Calcular permiso para revisión de tareas
        $user = Auth::user();
        $canreviewTasks = $user ? $user->hasPermissionInOrganization('Review_tasks', $project->organization_id) : false;

        return view('projects.show', [
            'project' => $project,
            'task' => $task,
            'canreviewTasks' => $canreviewTasks,
        ]);
    }

public function index()
{
    $organizations = auth()->user()->organizations()
                        ->with(['projects' => function($query) {
                            $query->active()->withCount('tasks');
                        }])
                        ->whereHas('projects')
                        ->get();
    
    $archivedProjects = auth()->user()->projects()
                        ->archived()
                        ->with(['organization', 'users'])
                        ->get();
    
    return view('projects.index', compact('organizations', 'archivedProjects'));
}
public function archive(Project $project)
{
    $this->authorize('update', $project);
    
    $project->update([
        'archived' => true,
        'archived_at' => now()
    ]);
    
    return redirect()->route('projects.index')
        ->with('success', 'El proyecto ha sido archivado correctamente.');
}

// Método para restaurar
public function restore(Project $project)
{
    $this->authorize('update', $project);
    
    $project->update([
        'archived' => false,
        'archived_at' => null
    ]);
    
    return redirect()->route('projects.index')
        ->with('success', 'El proyecto ha sido restaurado correctamente.');
}

}
