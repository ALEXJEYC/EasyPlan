<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\{Chat, Message, Membership, Organization, Project, Task, TaskAssignment, TaskReview};
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password',];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //chats

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class);
    }   
    // mensajes
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    // miembro
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    //organizaciones
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'memberships')
                    ->using(Membership::class)
                    ->withPivot('custom_role_id')
                    ->withTimestamps();
    }

    //proyectos en los que participa 
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withTimestamps();
    }
    public function ownedOrganizations()
    {
        return $this->hasMany(OrganizationOwner::class);
    }
    // public function tasks()
    // {
    //     return $this->belongsToMany(Task::class, 'task_user')->withTimestamps()->withPivot('observation');
    // }
    // public function assignedTasks()
    // {
    //     return $this->belongsToMany(Task::class)->withTimestamps()->withPivot('status');
    // }
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
                    ->withTimestamps()
                    ->withPivot('observation', 'status');
    }

    public function taskEvidences()
    {
        return $this->hasMany(TaskEvidence::class);
    }

    public function reviewedTasks()
    {
        return $this->hasMany(TaskReview::class, 'reviewer_id');
    }
    public function feedbacksSent()
    {
        return $this->hasMany(TaskFeedback::class, 'from_user_id');
    }
    public function taskUsers()
{
    return $this->hasMany(TaskUser::class);
}


    public function hasPermissionInOrganization(string $permission, int $organizationId): bool
    {
        $organization = Organization::find($organizationId);
        
        // Si es el owner, tiene todos los permisos
        if ($organization->isOwner($this)) {
            return true;
        }

        // Verificar membresía
        $membership = $this->memberships()
            ->where('organization_id', $organizationId)
            ->first();

        if (!$membership || !$membership->customRole) {
            return false;
        }

        // Verificar permisos del rol
        return $membership->customRole->permissions()
            ->where('name', $permission)
            ->exists();
    }




}
