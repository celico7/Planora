<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->with('tasks')->get();
        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'begining' => 'required|date',
        ]);

        $end_date = Carbon::parse($validated['begining'])->addDays(14);

        $project->sprints()->create([
            'nom' => $validated['nom'],
            'begining' => $validated['begining'],
            'end' => $end_date,
        ]);

        return redirect()->route('projects.index', $project)->with('success', 'Sprint créé');
    }

public function show($projectId, $sprintId)
{
    $sprint = \App\Models\Sprint::where('project_id', $projectId)
        ->where('id', $sprintId)
        ->firstOrFail();

    // Récupère les tâches liées au sprint
    $tasks = $sprint->tasks ?? collect();

    $total = $tasks->count();
    $done = $tasks->where('statut', 'terminé')->count();
    $inProgress = $tasks->where('statut', 'en cours')->count();
    $todo = $tasks->where('statut', 'à faire')->count();

    $progress = $total > 0 ? round(($done / $total) * 100, 1) : 0;

    return view('sprints.show', compact(
        'sprint', 'tasks', 'progress', 'done', 'inProgress', 'todo', 'total'
    ));
}


}
