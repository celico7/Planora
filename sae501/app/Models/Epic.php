<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epic extends Model
{
    protected $fillable = [
    'nom',
    'description',
    'begining',
    'end',
    'statut',
    'project_id',
    'sprint_id',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function sprint() {
    return $this->belongsTo(Sprint::class);
    }

}

