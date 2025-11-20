<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Epic;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;

class EpicController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $epics = $project->epics()->latest()->get();
        return view('epics.index', compact('epics', 'project'));
    }

    public function create(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);
        return view('epics.create', compact('project', 'sprint'));
    }

    public function store(Request $request, Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'begining' => 'required|date',
            'end' => 'required|date|after_or_equal:begining',
            'statut' => 'required|in:prévu,en cours,terminé',
        ]);

        $epic = Epic::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'begining' => $validated['begining'],
            'end' => $validated['end'],
            'statut' => $validated['statut'],
            'project_id' => $project->id,
            'sprint_id' => $sprint->id,
        ]);

        return redirect()->route('projects.sprints.show', [$project, $sprint])
            ->with('success', 'Epic créé !');
    }

    public function show(Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('view', $project);
        return view('epics.show', compact('epic', 'project'));
    }

    public function edit(Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('update', $project);
        return view('epics.edit', compact('project', 'sprint', 'epic'));
    }

    public function update(Request $request, Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'begining' => 'required|date',
            'end' => 'required|date|after_or_equal:begining',
            'statut' => 'required|in:prévu,en cours,terminé',
        ]);

        $epic->update($validated);

        return redirect()->route('projects.sprints.show', [$project, $sprint])->with('success', 'Epic modifié !');
    }

    public function destroy(Project $project, Sprint $sprint, Epic $epic)
    {
        $this->authorize('update', $project);
        $epic->delete();
        return redirect()->route('projects.sprints.show', [$project, $sprint])->with('success', 'Epic supprimé !');
    }
}
