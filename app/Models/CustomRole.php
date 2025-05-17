<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRole extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'custom_permissions',
    ];

    protected $casts = [
        'custom_permissions' => 'array',
    ];

    // Relación con Organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'custom_role_id');
    }

    // Relación con CustomRolePermission (permisos extendidos)
    public function customRolePermissions(): HasMany
    {
        return $this->hasMany(CustomRolePermission::class, 'custom_role_id');
    }
}