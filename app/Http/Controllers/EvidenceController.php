<?php

namespace App\Http\Controllers;

use App\Models\TaskEvidence;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
public function download(TaskEvidence $evidence)
{
    // Asegúrate de que la ruta sea relativa a storage/app/
    $filePath = 'public/' . $evidence->file_path;

    if (!Storage::exists($filePath)) {
        abort(404, "El archivo no existe en: " . $filePath);
    }

    return Storage::download($filePath, $evidence->file_name);
}
}