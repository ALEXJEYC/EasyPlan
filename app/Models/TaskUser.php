<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    
    protected $table = 'task_user';
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'observation',
        'submitted_at'
    ];

    // Relación con Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Relación con User (usuario asignado)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con evidencias
    public function evidences()
    {
        return $this->hasMany(TaskEvidence::class, 'task_user_id');
    }
    // Relación con revisiones
    public function reviews()
    {
        return $this->hasMany(TaskReview::class);
    }
}
