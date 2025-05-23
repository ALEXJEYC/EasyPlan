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
        return $this->belongsTo(TaskUser::class);
    }

    // Relación con User (revisor)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    // public function task()
    // {
    //     return $this->belongsTo(Task::class);
    // }
    

}