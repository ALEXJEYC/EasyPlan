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
    public function archive(Project $project)
    {
        $project->update(['status' => 'archived']);
        return redirect()->back()->with('success', 'El proyecto ha sido archivado.');
    }

    public function unarchive(Project $project)
    {
        $project->update(['status' => 'activo']);
        return redirect()->back()->with('success', 'El proyecto ha sido desarchivado.');
    }
}
