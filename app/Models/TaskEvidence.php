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
        'file_name'
    ];

    // Relación con TaskUser
    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class);
    }
}