<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'deadline',
        'assigned_by',
        'priority',
        'ready',
        'submitted_at',
        'submitted_by', // Asegúrate de que esta columna exista en tu tabla
    ];
    public const STATUS = [
    'pending',
    'submitted',
    'approved',
    ];

    protected $casts = [
    'deadline' => 'datetime',
    'ready' => 'boolean',
    'submitted_at' => 'datetime',
    'status' => TaskStatus::class,
    // 'id' => 'integer',
    // 'status' => 'boolean',
    // 'priority' => 'integer',
    // 'ready' => 'boolean',
    // 'assigned_by' => 'integer',
    // 'title' => 'string',
    // 'description' => 'string',
    // 'deadline' => 'datetime',
    // 'assigned_by' => 'integer',
    // 'user_id' => 'integer',
    // 'id' => 'integer',
    // 'status' => 'boolean',
    // 'priority' => 'integer',
    // 'ready' => 'boolean',
];
// use App\Enums\TaskStatus;


    // Si quieres que siga siendo string en JSON o vistas:
    public function getRawOriginalStatus(): string
    {
        return $this->getOriginal('status');
    }


    // Relación con Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
        ->withTimestamps()
        ->withPivot('status', 'observation', 'submitted_at', 'id');
    }
    public function evidences(): HasManyThrough
    {
        // Una tarea tiene muchas evidencias a través de sus asignaciones de usuario
       return $this->hasManyThrough(
                    TaskEvidence::class, // Modelo final
                    TaskUser::class,     // Modelo intermedio
                    'task_id',           // Foreign key en TaskUser que apunta a Task
                    'task_user_id',      // Foreign key en TaskEvidence que apunta a TaskUser
                    'id',                // Local key en Task
                    'id'                 // Local key en TaskUser
);
    }
    public function reviews(): HasManyThrough
    {
        // Una tarea tiene muchas revisiones a través de sus asignaciones de usuario
        return $this->hasManyThrough(TaskReview::class, TaskUser::class);
    }
    public function taskUsers(): HasMany
    {
        return $this->hasMany(TaskUser::class);
    }
    public function submittedBy()
{
    return $this->belongsTo(User::class, 'submitted_by'); // asegúrate que tengas esa columna en tu tabla
}
public function submittedAtForUser($userId)
{
    return $this->taskUsers()
                ->where('user_id', $userId)
                ->value('submitted_at'); // o el campo que estés usando
}
public function getReviewerAttribute()
{
    // Devuelve el reviewer del primer review aprobado, o null
    return $this->reviews->first()?->reviewer;
}


}