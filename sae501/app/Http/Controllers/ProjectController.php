<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des projets de l'utilisateur connecté
     */
    public function index()
    {
        $projects = Project::forUser(Auth::user())->latest()->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Affiche le formulaire de création d'un projet
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

        $project->load(['sprints.tasks', 'users']);

        // Récupère toutes les tâches de tous les sprints
        $allTasks = $project->sprints->flatMap(fn($s) => $s->tasks);
        $totalTasks = $allTasks->count();

        // Détecte les bons noms de colonnes si status/priority diffèrent
        $statusColumn = 'status';
        if ($allTasks->pluck('status')->filter()->isEmpty()) {
            if (!$allTasks->pluck('statut')->filter()->isEmpty()) $statusColumn = 'statut';
            elseif (!$allTasks->pluck('state')->filter()->isEmpty()) $statusColumn = 'state';
        }
        $priorityColumn = 'priority';
        if ($allTasks->pluck('priority')->filter()->isEmpty()) {
            if (!$allTasks->pluck('priorite')->filter()->isEmpty()) $priorityColumn = 'priorite';
            elseif (!$allTasks->pluck('priority_level')->filter()->isEmpty()) $priorityColumn = 'priority_level';
        }

        // Normalise les statuts/priorités vers des slugs canoniques
        $normalizeStatus = function ($v) {
            $v = is_null($v) ? '' : strtolower(trim((string)$v));
            $map = [
                'todo' => ['todo','to do','à faire','a faire','afaire','0','not started','backlog'],
                'in_progress' => ['in_progress','in progress','en cours','doing','1','wip'],
                'done' => ['done','terminé','termine','2','finished','complete','completed'],
            ];
            foreach ($map as $slug => $aliases) {
                if (in_array($v, $aliases, true)) return $slug;
            }
            return 'todo'; // défaut
        };
        $normalizePriority = function ($v) {
            $v = is_null($v) ? '' : strtolower(trim((string)$v));
            $map = [
                'low' => ['low','basse','faible','0'],
                'medium' => ['medium','moyenne','normal','1'],
                'high' => ['high','haute','elevee','élevée','2'],
            ];
            foreach ($map as $slug => $aliases) {
                if (in_array($v, $aliases, true)) return $slug;
            }
            return 'medium'; // défaut
        };

        // Comptages normalisés
        $statusCounts = ['todo'=>0,'in_progress'=>0,'done'=>0];
        $priorityCounts = ['low'=>0,'medium'=>0,'high'=>0];

        foreach ($allTasks as $t) {
            $status = $normalizeStatus($t->{$statusColumn} ?? null);
            $priority = $normalizePriority($t->{$priorityColumn} ?? null);
            if (isset($statusCounts[$status])) $statusCounts[$status]++;
            if (isset($priorityCounts[$priority])) $priorityCounts[$priority]++;
        }

        $completedTasks = $statusCounts['done'] ?? 0;
        $globalProgress = $totalTasks ? round(($completedTasks / $totalTasks) * 100, 1) : 0.0;

        // Données prêtes pour Chart.js (labels FR + data)
        $statusChart = [
            'labels' => ['À faire', 'En cours', 'Terminé'],
            'data' => [ $statusCounts['todo'], $statusCounts['in_progress'], $statusCounts['done'] ],
        ];
        $priorityChart = [
            'labels' => ['Basse', 'Moyenne', 'Haute'],
            'data' => [ $priorityCounts['low'], $priorityCounts['medium'], $priorityCounts['high'] ],
        ];

        // Progression par sprint
        $sprintProgress = $project->sprints->map(function ($sprint) use ($statusColumn, $normalizeStatus) {
            $total = $sprint->tasks->count();
            $completed = $sprint->tasks->filter(function($t) use ($statusColumn, $normalizeStatus) {
                return $normalizeStatus($t->{$statusColumn} ?? null) === 'done';
            })->count();
            return [
                'name' => $sprint->nom,
                'total' => $total,
                'completed' => $completed,
                'percentage' => $total ? round(($completed / $total) * 100, 1) : 0,
            ];
        });

        // Log utile pour debug
        \Log::info('Project Stats (normalized)', [
            'status_column' => $statusColumn,
            'priority_column' => $priorityColumn,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'global_progress' => $globalProgress,
            'status_counts' => $statusCounts,
            'priority_counts' => $priorityCounts,
            'raw_status_values' => $allTasks->pluck($statusColumn)->unique()->values(),
            'raw_priority_values' => $allTasks->pluck($priorityColumn)->unique()->values(),
        ]);

        return view('projects.show', compact(
            'project',
            'statusChart',
            'priorityChart',
            'sprintProgress',
            'globalProgress',
            'totalTasks',
            'completedTasks'
        ));
    }

    /**
     * Formulaire d'édition d'un projet
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
