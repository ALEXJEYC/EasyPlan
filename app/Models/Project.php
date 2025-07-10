<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $casts = [
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    protected $dates = ['archived_at'];


    protected $fillable = ['organization_id', 'name', 'description', 'status'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(368)
                    ->height(232)
                    ->sharpen(10);
            });
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    // deberia ser hasOne ya que solo es un chat por proyecto
    public function getChatAttribute()
    {
        return $this->chats()->where('type', 'project')->first();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withTimestamps();
    }
    public function hasUser(User $user): bool
    {
        return $this->users()->where('id', $user->id)->exists();
    }
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
public function scopeActive($query)
{
    return $query->where('status', 'iniciado');
}

// Scope para proyectos archivados
public function scopeArchived($query)
{
    return $query->where('status', 'archivado');
}
public function files()
{
    return $this->hasMany(ProjectFile::class);
}
public function isOwner($user)
{
    return $this->user_id === $user->id;
}

}
