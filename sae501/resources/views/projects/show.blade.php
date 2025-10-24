@extends('layouts.app')

@section('title', $project->nom)

@section('content')
<div class="max-w-4xl mx-auto bg-gray-100 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">{{ $project->nom }}</h1>
    <p class="mb-6">{{ $project->description }}</p>

    <div class="mb-5">
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">Modifier</a>
        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-error">Supprimer</button>
        </form>
    </div>

    <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}" class="btn btn-primary mb-4">Créer un sprint</a>

    <h3 class="text-xl font-semibold mt-8 mb-4">Vos sprints :</h3>
    <ul class="space-y-2">
        @forelse($project->sprints as $sprint)
            <li>
                <a href="{{ route('projects.sprints.show', ['project' => $project->id, 'sprint' => $sprint->id]) }}" class="hover:underline text-[#0CBABA]">
                    <strong>{{ $sprint->nom }}</strong> - Du {{ $sprint->begining }} au {{ $sprint->end }}
                </a>
            </li>
        @empty
            <li class="text-gray-500">Aucun sprint disponible pour ce projet.</li>
        @endforelse
    </ul>
</div>
@endsection
