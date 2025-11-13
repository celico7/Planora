<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Epic;
use App\Models\Task;
use Carbon\Carbon;

class Roadmap extends Component
{
    public $project;
    public $sprints = [];
    public $epics = [];
    public $ganttTasks = [];
    public $timelineStart;
    public $timelineEnd;
    public $viewMode = 'Week';

    // Filtres
    public $filterStatus = 'all';
    public $filterSprint = 'all';
    public $searchTerm = '';

    // Statistiques
    public $stats = [];

    // Drag & Drop
    public $draggedItem = null;

    protected $listeners = ['refreshRoadmap' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project->load(['sprints.epics.tasks', 'epics.tasks']);
        $this->loadData();
        $this->calculateStats();
    }

    public function loadData()
    {
        $query = $this->project->sprints()->with(['epics.tasks']);

        if ($this->filterStatus === 'active') {
            $query->where('end', '>=', now());
        } elseif ($this->filterStatus === 'completed') {
            $query->where('end', '<', now());
        }

        $this->sprints = $query->orderBy('begining')->get();

        // CALCULER LA PROGRESSION ICI POUR CHAQUE SPRINT
        foreach ($this->sprints as $sprint) {
            $allTasks = $sprint->epics->flatMap->tasks;
            $total = $allTasks->count();
            $done = $allTasks->where('statut', 'terminé')->count();
            $progress = $total > 0 ? round(($done / $total) * 100) : 0;

            // Injecter directement sur l'objet
            $sprint->computed_progress = $progress;
            $sprint->computed_total = $total;
            $sprint->computed_done = $done;

            // DEBUG
            \Log::info("Sprint {$sprint->nom}: {$done}/{$total} = {$progress}%", [
                'statuts' => $allTasks->pluck('statut')->toArray()
            ]);

            // PAREIL POUR LES EPICS
            foreach ($sprint->epics as $epic) {
                $epicTasks = $epic->tasks;
                $epicTotal = $epicTasks->count();
                $epicDone = $epicTasks->where('statut', 'terminé')->count();
                $epicProgress = $epicTotal > 0 ? round(($epicDone / $epicTotal) * 100) : 0;

                $epic->computed_progress = $epicProgress;
                $epic->computed_total = $epicTotal;
                $epic->computed_done = $epicDone;
            }
        }

        $epicQuery = $this->project->epics()->with('tasks');

        if ($this->filterSprint !== 'all') {
            $epicQuery->where('sprint_id', $this->filterSprint);
        }

        if ($this->searchTerm) {
            $epicQuery->where('nom', 'like', '%' . $this->searchTerm . '%');
        }

        $this->epics = $epicQuery->get();

        $this->calculateTimeline();
        $this->prepareGanttData();
    }

    public function calculateTimeline()
    {
        $allDates = [];

        foreach ($this->sprints as $s) {
            $allDates[] = Carbon::parse($s->begining);
            $allDates[] = Carbon::parse($s->end);
        }

        foreach ($this->epics as $e) {
            $allDates[] = Carbon::parse($e->begining);
            $allDates[] = Carbon::parse($e->end);
        }

        if (count($allDates)) {
            $this->timelineStart = min($allDates)->subWeek()->startOfWeek();
            $this->timelineEnd = max($allDates)->addWeek()->endOfWeek();
        } else {
            $this->timelineStart = Carbon::now()->startOfWeek();
            $this->timelineEnd = Carbon::now()->addWeeks(8)->endOfWeek();
        }
    }

    public function calculateStats()
    {
        $allTasks = $this->project->epics->flatMap->tasks;

        $this->stats = [
            'total_sprints' => $this->project->sprints->count(),
            'active_sprints' => $this->project->sprints->where('end', '>=', now())->count(),
            'total_epics' => $this->project->epics->count(),
            'total_tasks' => $allTasks->count(),
            'completed_tasks' => $allTasks->where('statut', 'terminé')->count(),
            'in_progress_tasks' => $allTasks->where('statut', 'en cours')->count(),
            'todo_tasks' => $allTasks->where('statut', 'à faire')->count(),
            'completion_rate' => $allTasks->count() > 0
                ? round(($allTasks->where('statut', 'terminé')->count() / $allTasks->count()) * 100)
                : 0,
            'high_priority_tasks' => $allTasks->where('priorite', 'haute')->count(),
            'overdue_tasks' => $allTasks->filter(function($task) {
                return $task->echeance && Carbon::parse($task->echeance)->isPast() && $task->statut !== 'terminé';
            })->count(),
        ];
    }

    public function prepareGanttData()
    {
        $tasks = [];
        $colorIndex = 0;
        $colors = ['#4040b0ff', '#7517bdff', '#b04040ff', '#bd7517ff', '#40b040ff', '#17bd75ff'];

        foreach ($this->sprints as $sprint) {
            $progress = $sprint->computed_progress ?? 0;
            $total = $sprint->computed_total ?? 0;
            $done = $sprint->computed_done ?? 0;

            $sprintColor = $colors[$colorIndex % count($colors)];
            $colorIndex++;

            $tasks[] = [
                'id' => 'sprint-' . $sprint->id,
                'name' => 'Sprint ' . $sprint->nom,
                'start' => $sprint->begining,
                'end' => $sprint->end,
                'progress' => $progress,
                'custom_class' => 'sprint-bar',
                'type' => 'sprint',
                'color' => $sprintColor,
                'total_tasks' => $total,
                'done_tasks' => $done,
            ];

            foreach ($sprint->epics as $epic) {
                $epicProgress = $epic->computed_progress ?? 0;
                $epicTotal = $epic->computed_total ?? 0;
                $epicDone = $epic->computed_done ?? 0;

                $tasks[] = [
                    'id' => 'epic-' . $epic->id,
                    'name' => 'Epic ' . $epic->nom,
                    'start' => $epic->begining,
                    'end' => $epic->end,
                    'progress' => $epicProgress,
                    'custom_class' => 'epic-bar',
                    'type' => 'epic',
                    'dependencies' => 'sprint-' . $sprint->id,
                    'total_tasks' => $epicTotal,
                    'done_tasks' => $epicDone,
                    'color' => $this->adjustColor($sprintColor, 30),
                ];
            }

            if ($progress >= 100) {
                $tasks[] = [
                    'id' => 'milestone-' . $sprint->id,
                    'name' => '🚀 Release ' . $sprint->nom,
                    'start' => $sprint->end,
                    'end' => $sprint->end,
                    'progress' => 100,
                    'custom_class' => 'milestone-bar',
                    'type' => 'milestone',
                    'dependencies' => 'sprint-' . $sprint->id,
                    'color' => '#14b8a6',
                ];
            }
        }

        $this->ganttTasks = $tasks;
    }

    private function adjustColor($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $percent));
        $g = max(0, min(255, $g + $percent));
        $b = max(0, min(255, $b + $percent));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    public function changeViewMode($mode)
    {
        $allowed = ['Day','Week','Month'];
        $mode = in_array($mode, $allowed) ? $mode : 'Week';
        $this->viewMode = $mode;
        $this->dispatch('updateGanttView', mode: $mode);
    }

    public function viewToday()
    {
        $this->viewMode = 'Day';
        $this->dispatch('updateGanttView', mode: 'Day');
        $this->dispatch('centerToday');
    }

    public function applyFilters()
    {
        $this->loadData();
        $this->calculateStats();
        $this->dispatch('ganttRefresh');
    }

    public function resetFilters()
    {
        $this->filterStatus = 'all';
        $this->filterSprint = 'all';
        $this->searchTerm = '';
        $this->applyFilters();
    }

    public function deleteEpic($epicId)
    {
        Epic::findOrFail($epicId)->delete();
        session()->flash('success', 'Epic supprimé !');
        $this->loadData();
        $this->calculateStats();
        $this->dispatch('ganttRefresh');
    }

    public function deleteSprint($sprintId)
    {
        Sprint::findOrFail($sprintId)->delete();
        session()->flash('success', 'Sprint supprimé !');
        $this->loadData();
        $this->calculateStats();
        $this->dispatch('ganttRefresh');
    }

    public function exportData()
    {
        $data = [
            'project' => $this->project->nom,
            'sprints' => $this->sprints->map(function($sprint) {
                return [
                    'nom' => $sprint->nom,
                    'debut' => $sprint->begining,
                    'fin' => $sprint->end,
                    'progress' => $sprint->computed_progress ?? 0,
                    'epics' => $sprint->epics->map(fn($e) => $e->nom)->toArray(),
                ];
            }),
            'stats' => $this->stats,
        ];

        return response()->json($data);
    }

    public function render()
    {
        return view('livewire.roadmap');
    }
}
