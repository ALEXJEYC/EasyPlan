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
//ESTE MODELO NO ESTA SIRVIENDO EN NINGUN LUGAR, PERO SE DEJA POR SI EN ALGUN MOMENTO SE NECESITA
//EN LA SIGUIENTE ENTREGA DESPUES DE UNA EVALUACION SERA ELIMINADO SI NO SE UTILIZA