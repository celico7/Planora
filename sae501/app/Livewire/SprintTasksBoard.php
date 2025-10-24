<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sprint;
use App\Models\Epic;
use App\Models\Task;

class SprintTasksBoard extends Component
{
    public $sprint;
    public $epics;
    public $showStatutDropdown = [];
    public $showPrioriteDropdown = [];

    protected $listeners = ['taskUpdated' => '$refresh'];

    public function mount(Sprint $sprint)
    {
        $this->sprint = $sprint;
        $this->epics = $sprint->epics()->with('tasks')->get();
    }

    public function updateTask($taskId, $field, $value)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        $task->{$field} = $value;
        $task->save();

        $this->closeDropdown($field, $taskId); 
        //$this->emit('taskUpdated');
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

