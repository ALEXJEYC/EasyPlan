<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Organization extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'type', 'description'];
    //funcion para asignarle imagenes a las organizaciones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(368)
                    ->height(232)
                    ->sharpen(10);
            });
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')
                    ->using(Membership::class)
                    ->withPivot('custom_role_id')
                    ->withTimestamps();
    }
    public function customRoles()
    {
        return $this->hasMany(CustomRole::class);
    }
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function ownerRelation()
    {
        return $this->hasOne(OrganizationOwner::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($organization) {
            // Asignar al usuario creador como owner
            $user = auth()->user();
            
            OrganizationOwner::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id
            ]);
            
            // Agregar también como miembro (opcional, dependiendo de tu lógica)
            $organization->members()->attach($user->id);

            // Crear chat grupal por defecto
            $chat = Chat::create([
                'type' => 'group',
                'name' => $organization->name . ' General',
                'organization_id' => $organization->id,
                'created_by' => $user->id,
            ]);

            $chat->users()->attach($user);
        });

    }
        public function isOwner(User $user): bool
        {
            return $this->ownerRelation->user_id === $user->id;
        }
}