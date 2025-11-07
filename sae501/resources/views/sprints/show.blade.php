@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-secondary">{{ $sprint->nom }}</h1>
    <p class="mb-6 text-gray-600">Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    <!-- Actions sprint -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
            class="px-4 py-2 rounded bg-white shadow hover:bg-primary/10 text-primary border border-primary/30 transition">
            <i class="bi bi-plus-circle-fill mr-1"></i>Créer un epic
        </a>
        
        @if($sprint->epics()->count() > 0)
        <a href="{{ route('projects.sprints.kanban', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
            class="px-4 py-2 rounded bg-primary shadow hover:bg-primary/90 text-white transition">
            <i class="bi bi-kanban-fill mr-1"></i>Vue Kanban
        </a>
        @endif
    </div>

    <h3>Vue spécifique par Epics :</h3>

    @if($sprint->epics()->count() > 0)
        @livewire('sprint-tasks-board', ['sprintId' => $sprint->id])
    @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <i class="bi bi-inbox text-5xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 text-lg mb-4">Aucun epic dans ce sprint pour le moment.</p>
            <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                class="inline-block px-6 py-3 rounded bg-primary text-white hover:bg-primary/90 transition">
                <i class="bi bi-plus-circle-fill mr-2"></i>Créer votre premier epic
            </a>
        </div>
    @endif

    <hr class="my-10">

    <h3 class="text-xl font-bold mb-4 flex items-center">
        <i class="bi bi-kanban-fill mr-2"></i> Tableau Kanban global du sprint
    </h3>
    
</div>

@endsection

