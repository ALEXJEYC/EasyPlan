<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_user_id',
        'reviewed_by',
        'status',
        'comments',
        // 'reviewed_at'
    ];

    // Relación con TaskUser
    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class, 'task_user_id');
    }

    // Para acceder directo a la tarea a través de taskUser
    public function task()
    {
        return $this->hasOneThrough(Task::class, TaskUser::class, 'id', 'id', 'task_user_id', 'task_id');
    }

    // Relación con el revisor (usuario que revisa)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    

}