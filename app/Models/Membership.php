<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class Membership extends Pivot
{
    protected $table = 'memberships';

    protected $fillable = ['user_id', 'organization_id', 'custom_role_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function customRole()
    {
        return $this->belongsTo(CustomRole::class, 'custom_role_id');
    }
}