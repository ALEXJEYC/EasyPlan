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
        'completed_at',
    ];

    // Relación con Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot('status');
    }
    public function evidences()
    {
        return $this->hasMany(TaskEvidence::class);
    }

    public function reviews()
    {
        return $this->hasMany(TaskReview::class);
    }

    public function approvedReview()
    {
        return $this->hasOne(TaskReview::class)->where('status', 'approved');
    }


}