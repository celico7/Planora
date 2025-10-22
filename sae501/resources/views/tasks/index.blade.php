@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tâches du sprint : {{ $sprint->nom }}</h1>

    <a href="{{ route('tasks.create', ['project' => $projectId, 'sprint' => $sprint->id]) }}" class="btn btn-success mb-3">Créer une tâche</a>

    <ul class="list-group">
        @forelse($tasks as $task)
            <li class="list-group-item">
                <strong>{{ $task->nom }}</strong> — {{ $task->statut }} <br>
                {{ $task->description }} <br>
                Responsable : {{ $task->responsable->name ?? 'Non défini' }} <br>
                Priorité : {{ $task->priorite }} — Échéance : {{ $task->echeance }}
            </li>
        @empty
            <li class="list-group-item">Aucune tâche pour ce sprint.</li>
        @endforelse
    </ul>
</div>
@endsection
