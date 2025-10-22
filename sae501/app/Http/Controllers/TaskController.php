<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Affiche le formulaire de création
    public function create($projectId, $sprintId)
    {
        $project = Project::findOrFail($projectId);
        $sprint = Sprint::findOrFail($sprintId);

        return view('tasks.create', compact('project', 'sprint'));
    }

    // Crée la tâche dans la BDD
    public function store(Request $request, $projectId, $sprintId)
{
    // Validation des données envoyées par le formulaire
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'statut' => 'required|in:à faire,en cours,terminé',
        'responsable_id' => 'required|exists:users,id',
        'echeance' => 'required|date',
        'priorite' => 'required|in:basse,moyenne,haute',
    ]);

    // Associer la tâche au sprint
    $validated['sprint_id'] = $sprintId;

    // Création de la tâche dans la base
    Task::create([
        'nom' => $validated['nom'],
        'description' => $validated['description'] ?? null,
        'statut' => $validated['statut'],
        'priorite' => $validated['priorite'],
        'echeance' => $validated['echeance'],
        'responsable_id' => $validated['responsable_id'],
        'sprint_id' => $validated['sprint_id'],
    ]);

    // Rediriger vers la page du sprint (pour voir la progression à jour et la liste des tâches)
    return redirect()->route('sprints.show', [
        'project' => $projectId,
        'sprint' => $sprintId
    ])->with('success', 'Tâche créée avec succès !');
}


    // Liste les tâches
    public function index($projectId, $sprintId)
    {
        $sprint = Sprint::with('tasks')->findOrFail($sprintId);
        $tasks = $sprint->tasks;

        return view('tasks.index', compact('sprint', 'tasks', 'projectId'));
    }

    
}
