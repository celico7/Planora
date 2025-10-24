@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-2 text-[#380036]">{{ $sprint->nom }}</h1>
    <p class="mb-6 text-gray-600">Durée : {{ $sprint->begining }} → {{ $sprint->end }}</p>

    <!-- Actions sprint -->
    <a href="{{ route('projects.sprints.epics.create', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
        class="px-4 py-2 rounded bg-white shadow hover:bg-[#0cbaba]/10 text-[#0cbaba] border border-[#0cbaba]/30 transition">Créer un epic</a>

    <!-- Kanban Epics/Tasks -->
    <div class="flex gap-6 overflow-x-auto py-4">
        @forelse($sprint->epics as $epic)
            <div class="min-w-[18rem] bg-[#f5f7fa] rounded-lg shadow p-3 flex-shrink-0">
                <h2 class="font-bold mb-2 text-[#0cbaba]">{{ $epic->nom }}</h2>
                
                <div class="text-xs text-gray-500 mb-2">{{ $epic->description }}</div>
                <div class="mb-4 font-semibold text-gray-400 flex gap-2">
                    <span>{{ $epic->begining }}</span> <span>→</span> <span>{{ $epic->end }}</span>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($epic->tasks as $task)
                        <div class="bg-white shadow rounded p-2 text-sm flex flex-col gap-1">
                            <div class="font-semibold text-[#380036]">{{ $task->nom }}</div>
                            <div class="text-xs text-gray-500">{{ $task->description }}</div>
                            <div class="flex gap-2 items-center mt-1">
                                <span class="px-2 py-1 rounded bg-[#0cbaba]/20 text-[#0cbaba] text-xs font-semibold">{{ ucfirst($task->statut) }}</span>
                                <span class="px-2 py-1 rounded bg-[#380036]/10 text-[#380036] text-xs">{{ ucfirst($task->priorite) }}</span>
                                <span class="ml-auto text-xs text-gray-500">Échéance : {{ $task->echeance }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-400 italic text-xs">Aucune tâche</div>
                    @endforelse
                </div>
               <div class="p-2 mt-4 rounded bg-gray-200 shadow transition-colors duration-200 group hover:bg-gray-100 z-10">
                <div class="flex items-center gap-3">
                    <i class="bi bi-plus-circle-fill text-gray-600 group-hover:text-[#0cbaba] text-lg"></i>
                    <a href="{{ route('projects.sprints.epics.tasks.create', [
                            'project' => $project->id,
                            'sprint' => $sprint->id,
                            'epic' => $epic->id]) }}"
                    class="block text-sm font-medium text-[#380036] group-hover:text-[#0cbaba]">
                        Ajouter une tâche à l'{{ $epic->nom }}
                    </a>
                </div>
            </div>
        </div>
        @empty
            <div class="text-gray-300 text-lg italic">Aucun epic dans ce sprint.</div>
        @endforelse
    </div>
</div>
@endsection
