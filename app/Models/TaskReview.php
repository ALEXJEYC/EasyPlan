<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_user_id',
        'reviewer_id',
        'status',
        'feedback',
        'reviewed_at'
    ];

    // Relación con TaskUser
    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class);
    }

    // Relación con User (revisor)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}