<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class KanbanBoard extends Component
{
    public $sprintId;
    public $tasks;
    public $showStatutDropdown = [];
    public $showPrioriteDropdown = [];

    public function mount($sprintId)
    {
        $this->sprintId = $sprintId;
        $this->loadTasks();
    }

    public function render()
    {
        // Sécuriser les clés pour éviter les erreurs de type "Undefined array key"
        $grouped = [
            'à faire' => collect(),
            'en cours' => collect(),
            'terminé' => collect(),
        ];

        foreach ($this->tasks as $task) {
            $statut = mb_strtolower(trim($task->statut));

            if (in_array($statut, ['à faire', 'a faire', 'todo'])) $statut = 'à faire';
            elseif (in_array($statut, ['en cours', 'en-cours', 'doing', 'in progress'])) $statut = 'en cours';
            elseif (in_array($statut, ['terminé', 'termine', 'fait', 'done'])) $statut = 'terminé';
            else $statut = 'à faire'; // fallback si statut inconnu

            $grouped[$statut]->push($task);
        }

        return view('livewire.kanban-board', [
            'tasks' => $grouped,
        ]);
    }

    private function loadTasks()
    {
        $this->tasks = Task::query()
            ->where(function ($q) {
                $q->where('sprint_id', $this->sprintId)
                ->orWhereHas('epic', fn($epic) => $epic->where('sprint_id', $this->sprintId));
            })
            ->with('epic')
            ->get();
    }


    public function updateTask($taskId, $field, $value)
    {
        $task = Task::findOrFail($taskId);
        $task->{$field} = $value;
        $task->save();

        $this->closeDropdown($field, $taskId);
        $this->loadTasks();
    }

    public function closeDropdown($type, $taskId)
    {
        if ($type === 'statut') {
            $this->showStatutDropdown[$taskId] = false;
        } elseif ($type === 'priorite') {
            $this->showPrioriteDropdown[$taskId] = false;
        }
    }
}
