<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description', 'owner_id'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')
                    ->withPivot('role')
                    ->withTimestamps();
    }
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    // public function users()
    // {
        // return $this->belongsToMany(User::class, 'organization_user')->withTimestamps();
    // }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function owner()
    {   
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }
    // public function hasUser(User $user): bool
    protected static function boot()
    {
        parent::boot();

        static::created(function ($organization) {
            // Crear chat grupal por defecto
            $user = auth()->user();
            
            $chat = Chat::create([
                'type' => 'group',
                'name' => $organization->name . ' General',
                'organization_id' => $organization->id,
                'created_by' => $user->id,
            ]);

            // Añadir al creador del chat
            $chat->users()->attach($user);
        });
    }
}