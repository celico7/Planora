@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-secondary dark:text-primary">{{ $sprint->nom }}</h1>
    <p class="mb-6 text-gray-600 dark:text-dark-muted">Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    <!-- Actions sprint -->
    @can('update', $project)
    <div class="flex gap-3 mb-6">
        <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
            class="px-4 py-2 rounded bg-white dark:bg-dark-card shadow dark:shadow-none hover:bg-primary/10 dark:hover:bg-primary/20 text-primary border border-primary/30 dark:border-primary/50 transition">
            <i class="bi bi-plus-circle-fill mr-1"></i>Créer un epic
        </a>
    @endcan

        @if($sprint->epics()->count() > 0)

        @endif
    </div>

    <h3 class="text-gray-800 dark:text-dark-text mb-4">Vue spécifique par Epics + Kanban :</h3>

    @if($sprint->epics()->count() > 0)
        @livewire('sprint-tasks-board', ['sprintId' => $sprint->id])
    @else
        <div class="bg-gray-50 dark:bg-dark-card border-2 border-dashed border-gray-300 dark:border-dark-border rounded-lg p-8 text-center">
            <i class="bi bi-inbox text-5xl text-gray-400 dark:text-dark-muted mb-3"></i>
            <p class="text-gray-600 dark:text-dark-muted text-lg mb-4">Aucun epic dans ce sprint pour le moment.</p>
            <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                class="inline-block px-6 py-3 rounded bg-primary text-white hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                <i class="bi bi-plus-circle-fill mr-2"></i>Créer votre premier epic
            </a>
        </div>
    @endif

</div>

@endsection

