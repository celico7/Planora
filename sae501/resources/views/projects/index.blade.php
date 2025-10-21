@extends('layouts.app')

@section('content')
<h1>Mes projets</h1>

<a href="{{ route('projects.create') }}"> Nouveau projet</a>

<ul>
    @foreach($projects as $project)
        <li>
            <strong>{{ $project->nom }}</strong> - {{ $project->description }}
            <a href="{{ route('projects.edit', $project->id) }}"> Modifier</a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit"> Supprimer</button>
            </form>
        </li>
    @endforeach
</ul>

<x-secondary-button>
    <a  href="{{ route('sprints.create', $project->id) }}"> Créer un sprint</a>
</x-secondary-button>

<h3>
    Vos sprints :
</h3>

<ul>
    @if($project->sprints->isEmpty())
        <li>Aucun sprint disponible pour ce projet.</li>
    @else
        @foreach($project->sprints as $sprint)
            <li>
                <strong>{{ $sprint->nom }}</strong> - Du {{ $sprint->begining }} au {{ $sprint->end }}
                <a href="{{ route('tasks.create', ['project' => $sprint->project_id, 'sprint' => $sprint->id]) }}">
    ➕ Ajouter une tâche
</a>

            </li>
        @endforeach
    @endif
</ul>

@endsection
