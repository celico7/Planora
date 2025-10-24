<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\Epic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->with('tasks')->get();
        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project, Sprint $sprint)
{
    // Valeurs à zéro ou vides pour l’affichage correct
    $done = 0;
    $inProgress = 0;
    $todo = 0;
    $total = 1; // pour éviter la division par 0
    $progress = 0;

    return view('sprints.create', compact(
        'project', 'sprint', 'done', 'inProgress', 'todo', 'total', 'progress'
    ));
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

        return redirect()->route('projects.index', $project)
            ->with('success', 'Sprint créé');
    }

    public function show($projectId, $sprintId)
{
    $project = Project::findOrFail($projectId);
    $sprint = Sprint::where('project_id', $projectId)
        ->where('id', $sprintId)
        ->with(['tasks', 'epics.tasks'])
        ->firstOrFail();

    $tasks = $sprint->tasks ?? collect();
    $epics = $sprint->epics ?? collect();

    $normalized = $tasks->map(function ($t) {
        $raw = $t->statut ?? '';
        $raw = trim(mb_strtolower($raw));
        if (class_exists(\Transliterator::class)) {
            $trans = \Transliterator::createFromRules(':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;');
            $raw = $trans->transliterate($raw);
        }
        return $raw;
    });

    $counts = $normalized->countBy()->toArray();
    $todo = $counts['a faire'] ?? 0;
    $inProgress = $counts['en cours'] ?? 0;
    $done = $counts['termine'] ?? 0;
    $total = $tasks->count();
    $progress = $total > 0 ? round(($done / $total) * 100, 1) : 0;

    return view('sprints.show', compact(
        'project',
        'sprint',
        'tasks',
        'epics',
        'done',
        'inProgress',
        'todo',
        'total',
        'progress'
    ));
}


}
