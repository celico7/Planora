<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'statut',
        'priorite',
        'echeance',
        'sprint_id',
        'epic_id',
        'responsable_id', 
    ];

    protected $casts = [
        'echeance' => 'date',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function epic(): BelongsTo
    {
        return $this->belongsTo(Epic::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
