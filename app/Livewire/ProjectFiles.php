<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectFiles extends Component
{
    use WithFileUploads;

    public Project $project;
    public $uploadedFile;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function rules()
    {
        return [
            // Validamos que sea doc, docx, xls, xlsx, pdf o zip y que no pese más de 20MB
            'uploadedFile' => 'required|file|mimes:doc,docx,xls,xlsx,pdf,zip|max:20480',
        ];
    }

    public function messages()
    {
        return [
            'uploadedFile.required' => 'Debes seleccionar un archivo.',
            'uploadedFile.mimes' => 'El archivo debe ser de tipo: doc, docx, xls, xlsx, pdf, zip.',
            'uploadedFile.max' => 'El archivo no debe pesar más de 20MB.',
        ];
    }

    public function save()
    {
        $this->validate();

        $path = $this->uploadedFile->store('project_files/' . $this->project->id, 'public');

        ProjectFile::create([
            'project_id' => $this->project->id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'file_name' => $this->uploadedFile->getClientOriginalName(),
            'file_size' => $this->uploadedFile->getSize(),
            'file_type' => $this->uploadedFile->getMimeType(),
        ]);

        $this->reset('uploadedFile');

        $this->dispatch('notify', type: 'success', message: '¡Archivo subido con éxito!');
    }

    public function delete($fileId)
    {
        $file = ProjectFile::findOrFail($fileId);

        // Lógica de permisos: solo el que lo subió o un admin/dueño del proyecto puede borrar.
        // Por ahora, lo simplificaremos a que solo quien lo subió puede borrarlo.
        if ($file->user_id !== auth()->id() && !$this->project->isOwner(auth()->user())) {
             $this->dispatch('notify', type: 'error', message: 'No tienes permiso para eliminar este archivo.');
            return;
        }

        // Eliminar el archivo del disco
        Storage::disk('public')->delete($file->file_path);

        // Eliminar el registro de la BD
        $file->delete();

        $this->dispatch('notify', type: 'success', message: 'Archivo eliminado.');
    }

    public function render()
    {
        // Solo los miembros del proyecto pueden ver los archivos
        if (! $this->project->members->contains(auth()->user()) && !$this->project->isOwner(auth()->user())) {
            abort(403, 'No tienes acceso a este proyecto.');
        }

        $files = $this->project->files()->with('user')->latest()->get();

        return view('livewire.project-files', [
            'files' => $files
        ]);
    }
}