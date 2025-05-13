<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
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
