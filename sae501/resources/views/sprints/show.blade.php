@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $sprint->nom }}</h1>
    <p>Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    <h3>Progression du sprint</h3>
    <div class="progress mb-3" style="height: 25px;">
        <div class="progress-bar bg-success" style="width: {{ $progress }}%">
            {{ $progress }}%
        </div>
    </div>

    <ul>
        <li>✅ Terminées : {{ $done }}</li>
        <li>⚙️ En cours : {{ $inProgress }}</li>
        <li>📝 À faire : {{ $todo }}</li>
    </ul>

    <hr>

    <h3>Tâches du sprint</h3>
    <ul>
        @foreach($sprint->tasks as $task)
            <li>
                <strong>{{ $task->nom }}</strong> — {{ $task->statut }}
                <br>
                {{ $task->description }}
            </li>
        @endforeach
    </ul>
    <a href="{{ route('tasks.create', ['project' => $sprint->project_id, 'sprint' => $sprint->id]) }}">
         ➕ Ajouter une tâche
    </a>
</div>
@endsection
