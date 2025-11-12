@extends('layouts.app')

@section('title', $project->nom)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow-lg">

    {{-- Header Projet --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">{{ $project->nom }}</h1>
        <p class="text-gray-600 text-lg mb-4">{{ $project->description }}</p>
        <div class="flex gap-3">
            <a href="{{ route('projects.edit', $project->id) }}" class="rounded px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-900 border border-yellow-300 shadow-sm transition">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Supprimer ce projet&nbsp;?');" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="rounded px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 border border-red-300 shadow-sm transition">
                    <i class="bi bi-trash"></i> Supprimer le projet
                </button>
            </form>
        </div>
    </div>

    {{-- Bouton Créer un sprint --}}
    <div class="mb-8">
        <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
           class="rounded px-4 py-2 mb-4 bg-primary text-white hover:bg-primary/90 font-semibold shadow transition">
            <i class="bi bi-plus-circle mr-2"></i>Créer un sprint</a>
    </div>

    {{-- Liste des sprints --}}
    <h3 class="text-xl font-semibold mt-8 mb-4">Vos sprints :</h3>
    <ul class="space-y-4">
        @forelse($project->sprints as $sprint)
            <li>
                <div class="bg-gray-50 rounded shadow-sm flex flex-col md:flex-row md:items-center md:justify-between p-4 border border-gray-200 relative">
                    <div>
                        <div class="text-lg font-semibold text-primary mb-1">{{ $sprint->nom }}</div>
                        <div class="text-gray-500 text-sm">Du <span class="font-medium">{{ $sprint->begining }}</span> au <span class="font-medium">{{ $sprint->end }}</span></div>
                    </div>
                    <div class="flex gap-3 mt-3 md:mt-0 items-center">
                        <a href="{{ route('projects.sprints.show', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                        class="flex items-center space-x-1 rounded px-4 py-2 bg-primary text-white hover:bg-primary/80 text-sm font-semibold shadow transition">
                            <i class="bi bi-kanban-fill mr-2"></i>
                            <span>Vue Kanban</span>
                        </a>
                        <a href="{{ route('projects.roadmap', $project->id) }}" class="flex items-center space-x-1 rounded px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-semibold shadow transition opacity-60">
                            <i class="bi bi-calendar-event mr-2"></i>
                            <span>Roadmap</span>
                        </a>
                        <!-- Bouton menu Sprint -->
                        <div class="relative group">
                            <button class="p-2 rounded hover:bg-gray-200" onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle('hidden');">
                                <i class="bi bi-three-dots-vertical text-xl"></i>
                            </button>
                            <div class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-30 group-hover:block">
                                <form method="POST" action="{{ route('projects.sprints.destroy', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                    onsubmit="return confirm('Supprimer ce sprint ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left text-red-600 px-4 py-2">
                                        <i class="bi bi-trash mr-1"></i> Supprimer le sprint
                                    </button>
                                </form>
                                <a href="{{ route('projects.sprints.edit', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                    class="w-full text-left text-gray-600 px-4 py-2 flex items-center hover:bg-gray-100">
                                    <i class="bi bi-pencil mr-1"></i> Modifier le sprint
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

        @empty
            <li class="text-gray-500">Aucun sprint disponible pour ce projet.</li>
        @endforelse
    </ul>
</div>
@endsection
