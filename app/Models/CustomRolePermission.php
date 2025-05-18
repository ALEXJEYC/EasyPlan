<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Permission;
//tabla pivot
class CustomRolePermission extends Model
{
    protected $table = 'custom_role_permissions';

    protected $fillable = [
        'custom_role_id',
        'permission_id',
    ];

    // Relación con CustomRole
    public function customRole(): BelongsTo
    {
        return $this->belongsTo(CustomRole::class, 'custom_role_id');
    }

    // Relación con Spatie Permission (asumiendo tienes este modelo)
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}