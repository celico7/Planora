@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $sprint->nom }}</h1>
    <p>Durée : {{ $sprint->date_debut }} → {{ $sprint->date_fin }}</p>

    <h3>Progression du sprint</h3>

@php
    $done = $done ?? 0;
    $inProgress = $inProgress ?? 0;
    $todo = $todo ?? 0;
    $total = $total ?? 0;
    $progress = $progress ?? 0;
@endphp

<div class="progress mb-3" style="height: 25px;">
    <div class="progress-bar bg-success" style="width: {{ $progress }}%">
        {{ $progress }}%
    </div>
</div>

</div>

    <ul>
        <li>✅ Terminées : {{ $done }}</li>
        <li>⚙️ En cours : {{ $inProgress }}</li>
        <li>📝 À faire : {{ $todo }}</li>
    </ul>

    <hr>

    <h3>Créer une nouvelle tâche</h3>
    <form method="POST" action="{{ route('tasks.index', ['project' => $sprint->project_id, 'sprint' => $sprint->id]) }}">
        @csrf
        <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la tâche</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-control">
                <option value="todo">À faire</option>
                <option value="in_progress">En cours</option>
                <option value="done">Terminée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer la tâche</button>
    </form>

    <hr>

    <h3>Tâches du sprint</h3>
    <ul>
        @foreach($sprint->tasks as $task)
            <li>
                <strong>{{ $task->nom }}</strong> — {{ $task->status }}
                <br>
                {{ $task->description }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
