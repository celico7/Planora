<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $responsableFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filtersKey = 0; // clé pour forcer le re-render

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'responsableFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatingResponsableFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter', 'responsableFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
        $this->filtersKey++; // force Livewire à ré-instancier les inputs
    }

    public function render()
    {
        // Récupérer les projets accessibles par l'utilisateur
        $projectIds = Auth::user()->projects()->pluck('projects.id');

        $tasks = Task::query()
            ->whereHas('epic.sprint.project', function ($query) use ($projectIds) {
                $query->whereIn('projects.id', $projectIds);
            })
            ->with(['epic.sprint.project', 'responsable'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('statut', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priorite', $this->priorityFilter);
            })
            ->when($this->responsableFilter, function ($query) {
                $query->where('responsable_id', $this->responsableFilter);
            })
            ->when($this->dateTo, function ($query) {
                $query->where('echeance', '=', $this->dateTo);
            })
            ->orderBy('echeance', 'asc')
            ->paginate(15);

        // Récupérer tous les utilisateurs membres des projets de l'utilisateur
        $users = User::whereHas('projects', function ($query) use ($projectIds) {
            $query->whereIn('projects.id', $projectIds);
        })->orderBy('name')->get();

        return view('livewire.task-search', [
            'tasks' => $tasks,
            'users' => $users,
        ]);
    }
}
