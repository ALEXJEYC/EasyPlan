<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    protected $fillable = [
        'project_id',
        'completion_percentage',
        'overall_status',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}