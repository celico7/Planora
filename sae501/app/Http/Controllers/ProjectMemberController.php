<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectMemberController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Project $project)
    {
        $this->authorize('manageMembers', $project);

        $data = $request->validate([
            'email' => ['required','email'],
            'role'  => ['required','in:admin,membre,invite'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        // Vérifier si l'utilisateur est déjà membre
        if ($project->users()->where('utilisateur_id', $user->id)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        $project->users()->attach($user->id, ['role' => $data['role']]);

        return back()->with('success', 'Membre ajouté.');
    }

    public function update(Request $request, Project $project, User $user)
    {
        $this->authorize('manageMembers', $project);

        $data = $request->validate([
            'role'  => ['required','in:admin,membre,invite'],
        ]);

        // Empêcher de modifier le rôle du chef de projet
        if ($project->chef_projet === $user->id) {
            return back()->with('error', 'Le créateur du projet ne peut pas voir son rôle modifié.');
        }

        // Empêcher de perdre le dernier admin
        $isAdmin = $project->memberRole($user) === Project::ROLE_ADMIN;
        if ($isAdmin && $data['role'] !== Project::ROLE_ADMIN) {
            $admins = $project->users()->wherePivot('role', Project::ROLE_ADMIN)->count();
            if ($admins <= 1) {
                return back()->with('error', 'Impossible de retirer le dernier administrateur.');
            }
        }

        $project->users()->updateExistingPivot($user->id, ['role' => $data['role']]);
        return back()->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Project $project, User $user)
    {
        $this->authorize('manageMembers', $project);

        // Ne pas permettre de retirer le chef de projet
        if ($project->chef_projet === $user->id) {
            return back()->with('error', 'Le créateur du projet ne peut pas être retiré.');
        }

        if ($project->memberRole($user) === Project::ROLE_ADMIN) {
            $admins = $project->users()->wherePivot('role', Project::ROLE_ADMIN)->count();
            if ($admins <= 1) {
                return back()->with('error', 'Impossible de retirer le dernier administrateur.');
            }
        }

        $project->users()->detach($user->id);
        return back()->with('success', 'Membre retiré.');
    }
}
