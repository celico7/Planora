@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary">Tableau Kanban - {{ $sprint->nom }}</h1>
            <p class="text-gray-600">Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>
        </div>
        <a href="{{ route('projects.sprints.show', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
            class="px-4 py-2 rounded bg-white shadow hover:bg-gray-100 text-gray-700 border border-gray-300 transition">
            <i class="bi bi-arrow-left mr-1"></i>Retour aux epics
        </a>
    </div>

   @livewire('kanban-board', ['sprintId' => $sprint->id])

</div>
@endsection
