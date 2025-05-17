<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'deadline',
        'assigned_by',
    ];

    // Relación con Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Relación con User que asignó la tarea
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Asignaciones de la tarea (usuarios que la tienen asignada)
    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    // Revisiones de la tarea
    public function taskReviews(): HasMany
    {
        return $this->hasMany(TaskReview::class);
    }
}