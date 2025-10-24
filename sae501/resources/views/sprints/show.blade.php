@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-secondary">{{ $sprint->nom }}</h1>
    <p class="mb-6 text-gray-600">Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    <!-- Actions sprint -->
    <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
        class="px-4 py-2 rounded bg-white shadow hover:bg-primary/10 text-primary border border-primary/30 transition">Créer un epic</a>

@livewire('sprint-tasks-board', ['sprint' => $sprint])

@endsection
