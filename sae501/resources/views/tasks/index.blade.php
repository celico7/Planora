@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-text mb-2">Tâches du sprint : {{ $sprint->nom }}</h1>

            <a href="{{ route('tasks.create', ['project' => $projectId, 'sprint' => $sprint->id]) }}"
               class="inline-block mt-4 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 dark:hover:bg-primary/80 font-semibold shadow transition">
                <i class="bi bi-plus-circle mr-2"></i>Créer une tâche
            </a>
        </div>

        <ul class="space-y-4">
            @forelse($tasks as $task)
                <li class="bg-gray-50 dark:bg-dark-hover border border-gray-200 dark:border-dark-border rounded-lg p-6 hover:shadow-md dark:hover:shadow-lg transition">
                    <div class="mb-3">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-dark-text mb-2">{{ $task->nom }}</h3>
                        <div class="flex items-center gap-3 mb-3">
                            @php
                                $statusBg = match($task->statut) {
                                    'à faire' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                    'en cours' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                    'terminé' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                };
                                $priorityBg = match($task->priorite) {
                                    'basse' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                    'moyenne' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                    'haute' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusBg }}">{{ ucfirst($task->statut) }}</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $priorityBg }}">Priorité {{ ucfirst($task->priorite) }}</span>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-dark-muted mb-4">{{ $task->description ?: 'Aucune description' }}</p>

                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-dark-muted">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1">
                                <i class="bi bi-person-circle"></i>
                                <strong class="text-gray-700 dark:text-dark-text">{{ $task->responsable->name ?? 'Non défini' }}</strong>
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-calendar-event"></i>
                                Échéance : <strong class="text-gray-700 dark:text-dark-text">{{ $task->echeance }}</strong>
                            </span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-center py-12">
                    <div class="bg-gray-100 dark:bg-dark-hover rounded-full p-6 mx-auto w-fit mb-4">
                        <i class="bi bi-inbox text-5xl text-gray-400 dark:text-dark-muted"></i>
                    </div>
                    <p class="text-gray-500 dark:text-dark-muted text-lg font-medium">Aucune tâche pour ce sprint.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
