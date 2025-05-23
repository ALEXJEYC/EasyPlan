<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Task;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
         $task = $project->tasks()->first() ?? new Task();
        // Cargar proyecto junto con su chat del tipo 'project'
        $project->load(['chats' => function ($query) {
            $query->where('type', 'project');
        }]);
        return view('projects.show', [
            'project' => $project,
            'task' => $task
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
