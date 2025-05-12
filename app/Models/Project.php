<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'name', 'description'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withTimestamps();
    }
    public function hasUser(User $user): bool
    {
        return $this->users()->where('id', $user->id)->exists();
    }
    // public function hasUser(User $user): bool
    // {
        // return $this->users->contains($user);
    // }
}