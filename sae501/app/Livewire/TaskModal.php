<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TaskModal extends Component
{
    public $showModal = false;
    public $taskId;
    public $nom;
    public $description;
    public $statut;
    public $priorite;
    public $echeance;
    public $responsable_id;
    public $projectUsers = [];

    protected $listeners = ['openTask'];

    public function openTask($taskId)
    {
        $task = Task::with('sprint.project.users', 'assignee')->findOrFail($taskId);

        $this->taskId = $task->id;
        $this->nom = $task->nom;
        $this->description = $task->description;
        $this->statut = $task->statut;
        $this->priorite = $task->priorite;
        $this->echeance = $task->echeance ? $task->echeance->format('Y-m-d') : null;
        $this->responsable_id = $task->responsable_id;
        $this->projectUsers = $task->sprint->project->users;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'taskId', 'nom', 'description', 'statut', 'priorite', 'echeance', 'responsable_id', 'projectUsers']);
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:à faire,en cours,terminé',
            'priorite' => 'required|in:basse,moyenne,haute',
            'echeance' => 'nullable|date',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::findOrFail($this->taskId);
        $task->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'statut' => $this->statut,
            'priorite' => $this->priorite,
            'echeance' => $this->echeance,
            'responsable_id' => $this->responsable_id,
        ]);

        $this->dispatch('taskUpdated');
        $this->closeModal();
        session()->flash('message', 'Tâche mise à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.task-modal');
    }
}
