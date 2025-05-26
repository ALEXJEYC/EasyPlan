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
    // protected static function boot()
    // {
    // parent::boot();

    // static::created(function ($project) {
    //     $user = auth()->user();

    //     // Crear chat para el proyecto
    //     $chat = Chat::create([
    //         'type' => 'project',
    //         'name' => $project->name . ' Chat',
    //         'project_id' => $project->id,
    //         'organization_id' => $project->organization_id,
    //         'created_by' => $user->id,
    //     ]);

    //     // Añadir al creador del chat y a los usuarios seleccionados
    //     $chat->users()->attach($project->users->pluck('id'));
    // });

    // public function hasUser(User $user): bool
    // {
        // return $this->users->contains($user);
    // }
}