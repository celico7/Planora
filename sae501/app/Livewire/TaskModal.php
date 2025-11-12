<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\On;

class TaskModal extends Component
{
    public $showModal = false;
    public $task;
    public $editData = [];

    protected $rules = [
        'editData.nom' => 'required|string|max:255',
        'editData.description' => 'nullable|string',
        'editData.statut' => 'required',
        'editData.priorite' => 'required',
        'editData.echeance' => 'required|date',
    ];

    #[On('openTaskModal')]
    public function openTaskModal($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->editData = $this->task->only(['nom', 'description', 'statut', 'priorite', 'echeance']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updateTask()
    {
        $this->validate();
        $this->task->update($this->editData);
        $this->emitUp('taskUpdated');
        $this->closeModal();
    }

    public function deleteTask()
    {
        $this->task->delete();
        $this->emitUp('taskDeleted');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.task-modal');
    }
}
