<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, $projectId)
    {
        // Validation
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:à faire,en cours,terminé',
            'sprint_id' => 'required|exists:sprints,id',
        ]);

        // Création de la tâche
        Task::create($validated);

        // Récupération du sprint pour la redirection
        $sprint = Sprint::findOrFail($validated['sprint_id']);

        return redirect()->route('sprints.show', [
            'project' => $projectId,
            'sprint' => $sprint->id
        ])->with('success', 'Tâche créée avec succès !');
    }

    public function create($projectId, $sprintId)
{
    $project = Project::findOrFail($projectId);
    $sprint = Sprint::findOrFail($sprintId);

    return view('tasks.create', [
        'project' => $project,
        'sprint' => $sprint,
    ]);
}

public function index($projectId, $sprintId)
{
    $sprint = \App\Models\Sprint::with('project')->findOrFail($sprintId);
    $tasks = $sprint->tasks()->with('responsable')->get();

    return view('tasks.index', compact('sprint', 'tasks'));
}



}
