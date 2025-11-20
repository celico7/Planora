<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\Epic;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SprintController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $sprints = $project->sprints()->with('tasks')->get();
        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        $this->authorize('update', $project);
        // Valeurs à zéro ou vides pour l’affichage correct
        $done = 0;
        $inProgress = 0;
        $todo = 0;
        $total = 1; // pour éviter la division par 0
        $progress = 0;

        return view('sprints.create', compact(
            'project', 'done', 'inProgress', 'todo', 'total', 'progress'
        ));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
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

        return redirect()->route('projects.show', $project)
            ->with('success', 'Sprint créé');
    }

    public function show(Project $project, Sprint $sprint)
    {
        $this->authorize('view', $project);
        // Vérifier que le sprint appartient bien au projet
        if ($sprint->project_id !== $project->id) {
            abort(404);
        }

        // Charger les relations
        $sprint->load(['tasks', 'epics.tasks']);

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

    public function kanban(Project $project, Sprint $sprint)
    {
        // Vérifie que le sprint appartient bien au projet
        if ($sprint->project_id !== $project->id) {
            abort(404);
        }

        return view('sprints.kanban', compact('project', 'sprint'));
    }

    public function destroy(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);
        // Supprime le sprint
        $sprint->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Sprint supprimé avec succès !');
    }

    /**
     * Formulaire d’édition d’un sprint
     */
    public function edit(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);
        return view('sprints.edit', compact('project', 'sprint'));
    }

    /**
     * Met à jour un projet
     */
    public function update(Request $request, Project $project, Sprint $sprint)
    {

        $this->authorize('update', $project);
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'begining' => 'required|date',
            'end' => 'required|date|after_or_equal:begining',
        ]);

        $sprint->update($validated);

        return redirect()->route('projects.sprints.show', [$project->id, $sprint->id])
                        ->with('success', 'Sprint mis à jour avec succès !');
    }



}
