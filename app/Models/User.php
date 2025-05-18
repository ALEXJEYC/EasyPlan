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
    // tabla quue relaciona usuarios y tareas
    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }
    //tabla que relaciona los usarios y quien la revisa

    public function taskReviews()
    {
        return $this->hasMany(TaskReview::class, 'reviewer_id');
    }
    // tabla que relaciona los usuarios y las tareas que asigna
    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }
    public function ownedOrganizations()
    {
        return $this->hasMany(OrganizationOwner::class);
    }
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps()->withPivot('status');
    }

    public function hasPermissionInOrganization($permissionName, $organizationId)
    {
        $membership = $this->memberships()
            ->where('organization_id', $organizationId)
            ->first();

        if (!$membership || !$membership->customRole) {
            return false;
        }

        return $membership->customRole->permissions->contains('name', $permissionName);
    }


}
