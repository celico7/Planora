<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'epic_id',
        'sprint_id',
        'nom',
        'description',
        'responsable_id',
        'echeance',
        'statut',
        'priorite',
    ];

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function responsable()
    {
        return $this->belongsTo(\App\Models\User::class, 'responsable_id');
    }   

}
