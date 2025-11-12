<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des projets de l’utilisateur connecté
     */
    public function index()
    {
        $projects = Project::forUser(Auth::user())->latest()->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Affiche le formulaire de création d’un projet
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Enregistre un nouveau projet dans la base
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'chef_projet' => Auth::id(),
        ]);

        // Ajouter le créateur comme admin dans le pivot
        $project->users()->attach(Auth::id(), ['role' => Project::ROLE_ADMIN]);

        return redirect()->route('projects.show', $project)->with('success', 'Projet créé avec succès.');
    }

    /**
     * Affiche un projet précis
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * Formulaire d’édition d’un projet
     */
    public function edit(Project $project)
    {
        $this->authorize('updateSettings', $project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Met à jour un projet
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('updateSettings', $project);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);
        return redirect()->route('projects.show', $project)->with('success', 'Projet modifié.');
    }

    /**
     * Supprime un projet
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('home')->with('success', 'Projet supprimé.');
    }

    public function roadmap(Project $project)
    {
        $project->load('epics'); // Précharge les epics
        return view('projects.roadmap', compact('project'));
    }

}
