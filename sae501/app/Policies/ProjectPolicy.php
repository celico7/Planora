<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return $project->chef_projet === $user->id
            || $project->users()->where('utilisateur_id', $user->id)->exists();
    }

    // Pour modifier le contenu (sprints/epics/tâches) -> admin ou membre
    public function update(User $user, Project $project): bool
    {
        return $user->hasProjectRole($project, [Project::ROLE_ADMIN, Project::ROLE_MEMBER]);
    }

    // Pour modifier les réglages du projet (titre/description) -> admin uniquement
    public function updateSettings(User $user, Project $project): bool
    {
        return $user->hasProjectRole($project, [Project::ROLE_ADMIN]);
    }

    public function manageMembers(User $user, Project $project): bool
    {
        return $user->hasProjectRole($project, [Project::ROLE_ADMIN]);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasProjectRole($project, [Project::ROLE_ADMIN]);
    }
}
