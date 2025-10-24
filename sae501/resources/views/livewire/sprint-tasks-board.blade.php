<div class="flex gap-6 overflow-x-auto py-4">
    @forelse($epics as $epic)
        <div class="min-w-[18rem] bg-gray-50 rounded-lg shadow p-4 flex-shrink-0 border-t-4 border-primary">
            <h2 class="font-bold text-lg text-primary mb-2">{{ $epic->nom }}</h2>
            <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-200 text-xs font-medium text-gray-700">
                <i class="bi bi-list-check mr-1"></i>{{ $epic->tasks->count() }} tâches
            </span>
            <span class="inline-flex items-center px-2 py-1 rounded bg-green-200 text-xs font-medium text-green-800">
                <i class="bi bi-check-circle-fill mr-1"></i>{{ $epic->tasks->where('statut', 'terminé')->count() }}
            </span>
            <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 text-xs font-medium text-yellow-800">
                <i class="bi bi-gear-fill mr-1"></i>{{ $epic->tasks->where('statut', 'en cours')->count() }}
            </span>
            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-xs font-medium text-gray-700">
                <i class="bi bi-pencil mr-1"></i>{{ $epic->tasks->where('statut', 'à faire')->count() }}
            </span>
        </div>

            <p class="text-sm text-gray-500 mb-3">{{ $epic->description }}</p>
            <p class="mb-4 font-semibold text-gray-400 flex gap-2">{{ $epic->begining }} → {{ $epic->end }}</p>


            <div class="flex flex-col gap-3">
                @forelse($epic->tasks as $task)
                    <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-3 text-sm flex flex-col gap-2 hover:shadow-md transition-shadow">

                        <div class="font-semibold text-secondary">{{ $task->nom }}</div>
                        <div class="text-xs text-gray-500">{{ $task->description }}</div>
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Dropdown statut -->
                            <div wire:ignore.self class="relative">
                                <button class="px-2 py-1 rounded text-xs font-semibold text-white
                                        {{ $task->statut === 'terminé' ? 'bg-green-500' : ($task->statut === 'en cours' ? 'bg-yellow-500' : 'bg-gray-500') }}"
                                        wire:click="$set('showStatutDropdown.{{ $task->id }}', true)">
                                    <i class="bi bi-exclamation-circle-fill mr-1"></i>
                                    {{ ucfirst($task->statut) }}
                                    <i class="bi bi-chevron-down ml-1"></i>
                                </button>
                                @if(isset($showStatutDropdown[$task->id]) && $showStatutDropdown[$task->id])
                                <div class="absolute mt-1 bg-white border border-gray-200 rounded shadow-md w-28 z-10">
                                    @foreach(['à faire', 'en cours', 'terminé'] as $statut)
                                        <button type="button" wire:click="updateTask({{ $task->id }}, 'statut', '{{ $statut }}')" class="block w-full text-left px-3 py-1 hover:bg-gray-100 text-sm">
                                            {{ ucfirst($statut) }}
                                        </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            
                            <!-- Dropdown priorité -->
                            <div wire:ignore.self class="relative">
                                <button class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $task->priorite === 'haute' ? 'bg-red-500 text-white' : ($task->priorite === 'moyenne' ? 'bg-orange-400 text-white' : 'bg-gray-200 text-gray-700') }}"
                                        wire:click="$set('showPrioriteDropdown.{{ $task->id }}', true)">
                                    <i class="bi bi-arrow-up-circle-fill mr-1"></i>
                                    {{ ucfirst($task->priorite) }}
                                    <i class="bi bi-chevron-down ml-1"></i>
                                </button>
                                @if(isset($showPrioriteDropdown[$task->id]) && $showPrioriteDropdown[$task->id])
                                <div class="absolute mt-1 bg-white border border-gray-200 rounded shadow-md w-28 z-10">
                                    @foreach(['basse', 'moyenne', 'haute'] as $priorite)
                                        <button type="button" wire:click="updateTask({{ $task->id }}, 'priorite', '{{ $priorite }}')" class="block w-full text-left px-3 py-1 hover:bg-gray-100 text-sm">
                                            {{ ucfirst($priorite) }}
                                        </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <span class="ml-auto text-xs text-gray-500">Échéance : {{ $task->echeance }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400 italic text-xs">Aucune tâche</div>
                @endforelse
            </div>
            <div class="mt-4 p-2 text-sm text-secondary bg-gray-100 border border-dashed border-gray-300 rounded shadow transition-colors duration-200 group hover:bg-gray-200 transition">
                <a href="{{ route('projects.sprints.epics.tasks.create', [
                    'project' => $epic->project_id,
                    'sprint' => $epic->sprint_id,
                    'epic' => $epic->id]) }}">
                    <i class="bi bi-plus-circle-fill mr-1"></i>
                    Ajouter une tâche à {{ $epic->nom }}
                </a>
            </div>
        </div>
    @empty
        <div class="text-gray-400 text-lg italic">Aucun epic dans ce sprint.</div>
    @endforelse
</div>
