<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project_role', 'utilisateur_id', 'projet_id')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function hasProjectRole(Project $project, array|string $roles): bool
    {
        $roles = (array) $roles;

        // Si l'utilisateur est chef_projet, il est admin de facto
        if ($project->chef_projet === $this->id) {
            return in_array('admin', $roles, true) || in_array('*', $roles, true);
        }

        // Correction : utiliser projects.id au lieu de project_id
        $pivot = $this->projects()->where('projects.id', $project->id)->first()?->pivot;

        return $pivot && in_array($pivot->role, $roles, true);
    }
}
