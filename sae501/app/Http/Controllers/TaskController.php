<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Task;
use App\Models\Sprint;
use App\Models\Project;
use App\Models\Epic;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function create(Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('update', $project);
        return view('tasks.create', compact('project', 'sprint', 'epic'));
    }


    public function store(Request $request, Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('update', $project);

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

        return redirect()->route('projects.sprints.show', [$project, $sprint])
            ->with('success', 'Tâche créée avec succès !');
    }

    public function edit(Project $project, Sprint $sprint, Epic $epic, Task $task)
    {
        $this->authorize('update', $project);
        return view('tasks.edit', compact('project', 'sprint', 'epic', 'task'));
    }

    public function update(Request $request, Project $project, Sprint $sprint, Epic $epic, Task $task)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:à faire,en cours,terminé',
            'priorite' => 'required|string|max:255',
            'echeance' => 'required|date',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $task->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'statut' => $validated['statut'],
            'priorite' => $validated['priorite'],
            'echeance' => $validated['echeance'],
            'responsable_id' => $validated['responsable_id'] ?? null,
        ]);

        return redirect()->route('projects.sprints.show', [$project->id, $sprint->id])
            ->with('success', 'Tâche mise à jour avec succès !');
    }

    // Liste les tâches
    public function index($projectId, $sprintId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $sprint = Sprint::with('tasks')->findOrFail($sprintId);
        $tasks = $sprint->tasks;

        return view('tasks.index', compact('sprint', 'tasks', 'projectId'));
    }

    public function destroy(Project $project, Sprint $sprint, Epic $epic, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('projects.sprints.show', [$project, $sprint])
            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->statut = $request->input('status');
        $task->save();

        return response()->json(['success' => true]);
    }

}
