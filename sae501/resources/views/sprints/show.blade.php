@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $sprint->nom }}</h1>
    <p>Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    @livewire('sprint-tasks', ['sprint' => $sprint])

    <a href="{{ route('tasks.create', ['project' => $sprint->project_id, 'sprint' => $sprint->id]) }}">
         ➕ Ajouter une tâche
    </a>
</div>
@endsection
