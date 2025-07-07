<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskEvidence extends Model
{
    use HasFactory;
    protected $table = 'task_evidences';

    protected $fillable = [
        'task_user_id',
        'file_path',
        'file_name',
        'file_size',
        'file_type'
    ];
    public const ALLOWED_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/x-zip-compressed'
    ];

    // Relación con TaskUser
    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class);
    }
    // Obtener tamaño legible del archivo
    public function getFileSizeAttribute($value)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($value, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
