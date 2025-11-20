<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    public const ROLE_ADMIN   = 'admin';
    public const ROLE_MEMBER  = 'membre';
    public const ROLE_GUEST   = 'invite';

    protected $fillable = ['nom', 'description', 'chef_projet'];

    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_projet');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project_role', 'projet_id', 'utilisateur_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function epics()
    {
        return $this->hasMany(Epic::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function memberRole(User $user): ?string
    {
        if ($this->chef_projet === $user->id) {
            return self::ROLE_ADMIN;
        }
        return $this->users()->where('utilisateur_id', $user->id)->first()?->pivot->role;
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('chef_projet', $user->id)
            ->orWhereHas('users', fn($q) => $q->where('utilisateur_id', $user->id));
    }
}

