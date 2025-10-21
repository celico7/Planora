<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'status', 'sprint_id', 'project_id'];

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
