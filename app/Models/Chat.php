<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'name', 'organization_id', 'project_id', 'created_by'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_user')->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
//     public function users(): BelongsToMany
//     {
//         return $this->belongsToMany(User::class);
//     }

//     public function messages(): HasMany
//     {
//         return $this->hasMany(Message::class);
//     }
}
