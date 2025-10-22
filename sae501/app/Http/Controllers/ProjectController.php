<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Affiche la liste des projets de l’utilisateur connecté
     */
    public function index()
    {
        $projects = Project::where('chef_projet', Auth::id())->get();
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
    // Validation des données
    $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Création du projet
    $project = Project::create([
        'nom' => $request->nom,
        'description' => $request->description,
        'chef_projet' => auth()->id(), 
    ]);

    // Ajouter l'utilisateur connecté comme admin 
    $project->users()->attach(auth()->id(), ['role' => 'admin']);

    // Redirection vers la liste des projets + creation projets
    return redirect()->route('projects.index')->with('success', 'Projet créé avec succès !');
}



    /**
     * Affiche un projet précis
     */
    public function show(Project $project)
    {
        if ($project->chef_projet !== Auth::id()) {
            abort(403, 'Accès interdit');
        }

        return view('projects.show', compact('project'));
    }


    /**
     * Formulaire d’édition d’un projet
     */
    public function edit(Project $project)
    {
        if ($project->chef_projet !== Auth::id()) {
            abort(403, 'Accès interdit');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Met à jour un projet
     */
    public function update(Request $request, Project $project)
    {
        if ($project->chef_projet !== Auth::id()) {
            abort(403, 'Accès interdit');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index', $project)
                         ->with('success', 'Projet mis à jour avec succès !');
    }

    /**
     * Supprime un projet
     */
    public function destroy(Project $project)
    {
        // Supprime les liens avec les utilisateurs
        $project->users()->detach();

        // Supprime le projet
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès !');
    }

}
