<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'chef_projet'];

    // Un projet appartient à un chef de projet (user)
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

    // Un projet peut avoir plusieurs sprints
    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    // Un projet peut avoir plusieurs epics
    public function epics()
    {
        return $this->hasMany(Epic::class);
    }

    // Un projet peut avoir plusieurs tâches
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

