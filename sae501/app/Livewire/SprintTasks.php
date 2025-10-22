<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sprint;
use App\Models\Task;
use Transliterator;

class SprintTasks extends Component
{
    public Sprint $sprint;
    public $tasks;

    public $progress;
    public $done;
    public $inProgress;
    public $todo;
    public $total;

    // On monte le sprint et calcule les stats direct à l'init
    public function mount(Sprint $sprint)
    {
        $this->sprint = $sprint->load('tasks');
        $this->tasks = $this->sprint->tasks;
        $this->updateStats();
    }

    // Changement du statut d'une tâche
    public function updateStatut($taskId, $statut)
    {
        $task = Task::findOrFail($taskId);
        $task->statut = $statut;
        $task->save();

        // Recharge les tâches après modif
        $this->sprint->refresh();
        $this->tasks = $this->sprint->tasks;
        $this->updateStats();
    }

    // Calcul de la progression/barre
    public function updateStats()
    {
        $normalized = $this->tasks->map(function ($t) {
            $raw = $t->statut ?? '';
            $raw = trim(mb_strtolower($raw));
            if (class_exists(\Transliterator::class)) {
                $trans = \Transliterator::createFromRules(':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;');
                $raw = $trans->transliterate($raw);
            }
            return $raw;
        });

        $counts = $normalized->countBy()->toArray();
        $this->todo = $counts['a faire'] ?? 0;
        $this->inProgress = $counts['en cours'] ?? 0;
        $this->done = $counts['termine'] ?? 0;
        $this->total = count($this->tasks);
        $this->progress = $this->total > 0 ? round(($this->done / $this->total) * 100, 1) : 0;
    }

    public function render()
    {
        return view('livewire.sprint-tasks');
    }
}

