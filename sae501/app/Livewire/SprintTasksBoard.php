<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sprint;
use App\Models\Epic;
use App\Models\Task;

class SprintTasksBoard extends Component
{
    public $sprintId;
    public $epics;
    public $showStatutDropdown = [];
    public $showPrioriteDropdown = [];

    protected $listeners = ['taskUpdated' => '$refresh'];

    public function mount($sprintId)
    {
        $this->sprintId = $sprintId;

        // Charger les epics du sprint
        $this->epics = Epic::where('sprint_id', $this->sprintId)
            ->with('tasks')
            ->get();
    }

    public function updateTask($taskId, $field, $value)
    {
        $task = Task::findOrFail($taskId);
        $task->{$field} = $value;
        $task->save();

        $this->closeDropdown($field, $taskId);

        // Recharger les epics pour mettre à jour l'affichage
        $this->epics = Epic::where('sprint_id', $this->sprintId)
            ->with('tasks')
            ->get();
    }

    public function closeDropdown($type, $taskId)
    {
        if ($type === 'statut') {
            $this->showStatutDropdown[$taskId] = false;
        } elseif ($type === 'priorite') {
            $this->showPrioriteDropdown[$taskId] = false;
        }
    }

    private function kanbanColumns()
    {
        // Combine toutes les tâches de tous les epics du sprint
        $allTasks = collect();
        foreach ($this->epics as $epic) {
            $allTasks = $allTasks->merge($epic->tasks);
        }

        // On prépare un tableau associatif avec les colonnes du Kanban
        $kanban = [
            'à faire' => collect(),
            'en cours' => collect(),
            'terminé' => collect(),
        ];

        foreach ($allTasks as $task) {
            $statut = mb_strtolower(trim($task->statut));
            if (in_array($statut, ['à faire', 'a faire', 'todo'])) $statut = 'à faire';
            elseif (in_array($statut, ['en cours', 'en-cours', 'doing', 'in progress'])) $statut = 'en cours';
            elseif (in_array($statut, ['terminé', 'termine', 'fait', 'done'])) $statut = 'terminé';
            else $statut = 'à faire';
            $kanban[$statut]->push($task);
        }

        return $kanban;
    }

    public $openEpicId = null;

    public function toggleEpic($epicId)
    {
        $this->openEpicId = $this->openEpicId === $epicId ? null : $epicId;
    }

    public function openTask($taskId)
    {
        $this->dispatch('openTaskModal', taskId: $taskId);
    }

    public function render()
    {
        return view('livewire.sprint-tasks-board');
    }


}
