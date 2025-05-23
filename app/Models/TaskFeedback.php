<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_user_id',
        'from_user_id',
        'message',
    ];

    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
