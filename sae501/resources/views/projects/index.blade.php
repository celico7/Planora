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
@endsection
