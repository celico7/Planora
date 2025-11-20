<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $task->epic ? $user->can('view', $task->epic->sprint->project) : false;
    }

    public function update(User $user, Task $task): bool
    {
        if (!$task->epic) return false;

        $project = $task->epic->sprint->project;

        // Créateur du projet
        if ($project->chef_projet === $user->id) {
            return true;
        }

        // Admin ou Membre du projet
        $pivot = $project->users()->where('utilisateur_id', $user->id)->first()?->pivot;
        return $pivot && in_array($pivot->role, ['admin', 'membre']);
    }

    public function delete(User $user, Task $task): bool
    {
        if (!$task->epic) return false;

        $project = $task->epic->sprint->project;

        // Créateur du projet
        if ($project->chef_projet === $user->id) {
            return true;
        }

        // Admin ou Membre du projet
        $pivot = $project->users()->where('utilisateur_id', $user->id)->first()?->pivot;
        return $pivot && in_array($pivot->role, ['admin', 'membre']);
    }
}
