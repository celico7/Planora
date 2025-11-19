<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sprint;
use Illuminate\Auth\Access\HandlesAuthorization;

class SprintPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Sprint $sprint)
    {
        $project = $sprint->project;

        // Seul le créateur du projet et les admins du projet peuvent modifier
        if ($project->chef_projet === $user->id) {
            return true;
        }

        $pivot = $project->users()->where('utilisateur_id', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'admin';
    }

    public function delete(User $user, Sprint $sprint)
    {
        $project = $sprint->project;

        // Seul le créateur du projet et les admins du projet peuvent supprimer
        if ($project->chef_projet === $user->id) {
            return true;
        }

        $pivot = $project->users()->where('utilisateur_id', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'admin';
    }
}
