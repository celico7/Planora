<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Sprint;
use App\Models\Project;
use App\Models\Epic;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function create(Project $project, Sprint $sprint, Epic $epic)
    {
        return view('tasks.create', compact('project', 'sprint', 'epic'));
    }


    public function store(Project $project, Sprint $sprint, Epic $epic, Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:à faire,en cours,terminé',
            'priorite' => 'required|string|max:255',
            'echeance' => 'required|date',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'statut' => $validated['statut'],
            'priorite' => $validated['priorite'],
            'echeance' => $validated['echeance'],
            'project_id' => $project->id,
            'sprint_id' => $sprint->id,
            'epic_id' => $epic->id,
            'responsable_id' => $validated['responsable_id'] ?? null,
        ]);

        return redirect()->route('sprints.show', [$project, $sprint])
            ->with('success', 'Tâche créée avec succès !');
    }

    // Liste les tâches
    public function index($projectId, $sprintId)
    {
        $sprint = Sprint::with('tasks')->findOrFail($sprintId);
        $tasks = $sprint->tasks;

        return view('tasks.index', compact('sprint', 'tasks', 'projectId'));
    }

    
}
