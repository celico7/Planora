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

    public function render()
    {
        return view('livewire.sprint-tasks-board');
    }
}
