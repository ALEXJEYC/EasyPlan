<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'deadline',
        'assigned_by',
        'status',
        'priority',
        'redy',
    ];

    // Relación con Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps()->withPivot('status', 'observation', 'submitted_at', 'id');
    }
    public function evidences(): HasMany
    {
        // Una tarea tiene muchas evidencias a través de sus asignaciones de usuario
        return $this->hasManyThrough(TaskEvidence::class, TaskUser::class);
    }
    public function reviews(): HasMany
    {
        // Una tarea tiene muchas revisiones a través de sus asignaciones de usuario
        return $this->hasManyThrough(TaskReview::class, TaskUser::class);
    }
    public function taskUsers(): HasMany
    {
        return $this->hasMany(TaskUser::class);
    }



}