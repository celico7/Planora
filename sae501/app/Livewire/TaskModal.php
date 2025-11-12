<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskModal extends Component
{
    public $showModal = false;
    public $task;
    public $editData = [];

    public $members = [];

    protected $rules = [
        'editData.nom' => 'required|string|max:255',
        'editData.description' => 'nullable|string',
        'editData.statut' => 'required',
        'editData.priorite' => 'required',
        'editData.echeance' => 'required|date',
    ];

    protected $listeners = [
        'openTask' => 'openTask',
    ];

    public function hydrate()
    {
        if ($this->task) {
            $this->loadMembersFromTask();
        }
    }

    private function loadMembersFromTask(): void
    {
        $this->members = [];
        if (!$this->task) return;

        $project = optional(optional($this->task->epic)->sprint)->project;
        if ($project) {
            $members = $project->users()->get();

            if ($project->chef && !$members->contains('id', $project->chef->id)) {
                $members->push($project->chef);
            }

            $this->members = $members->unique('id')->sortBy('name')->values()->all();
        }
    }

    public function openTask(int $taskId)
    {
        $this->task = Task::with('epic.sprint.project.users')->findOrFail($taskId);

        $this->editData = [
            'nom'            => $this->task->nom,
            'description'    => $this->task->description,
            'statut'         => $this->task->statut,
            'priorite'       => $this->task->priorite,
            'echeance'       => optional($this->task->echeance)->format('Y-m-d') ?? $this->task->echeance,
            'responsable_id' => $this->task->responsable_id,
        ];

        $this->loadMembersFromTask();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updateTask()
    {
        $this->validate([
            'editData.nom'            => 'required|string|max:255',
            'editData.description'    => 'nullable|string',
            'editData.statut'         => 'required|in:à faire,en cours,terminé',
            'editData.priorite'       => 'required|in:basse,moyenne,haute',
            'editData.echeance'       => 'nullable|date',
            'editData.responsable_id' => 'nullable|exists:users,id',
        ]);

        $this->task->update([
            'nom'            => $this->editData['nom'],
            'description'    => $this->editData['description'] ?? null,
            'statut'         => $this->editData['statut'],
            'priorite'       => $this->editData['priorite'],
            'echeance'       => $this->editData['echeance'] ?? null,
            'responsable_id' => $this->editData['responsable_id'] ?: null,
        ]);

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
