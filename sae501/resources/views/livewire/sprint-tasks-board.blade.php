<div class="flex gap-6 overflow-x-auto py-4 w-full">

    {{-- Colonne unique pour toutes les Epics, empilées --}} 
    <div class="flex flex-col gap-6 min-w-[18rem] max-w-[18rem] flex-shrink-0">
        @foreach($epics as $epic)
            <div class="bg-gray-50 rounded-lg shadow p-4 border border-primary">
                <div class="flex justify-between items-center cursor-pointer" wire:click="toggleEpic({{ $epic->id }})">
                    <h2 class="font-bold text-lg text-primary mb-0 text-center flex-1">{{ $epic->nom }}</h2>
                    <button>
                        @if($openEpicId === $epic->id)
                            <i class="bi bi-chevron-up text-2xl"></i>
                        @else
                            <i class="bi bi-chevron-down text-2xl"></i>
                        @endif
                    </button>
                </div>
                @if($openEpicId === $epic->id)
                    <div class="transition-all duration-300 ease-in-out mt-4">
                        <div class="flex flex-col gap-2 items-center mb-2">
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
                        <p class="text-sm text-gray-500 my-3 text-center">{{ $epic->description }}</p>
                        <p class="mb-4 font-semibold text-gray-400 flex gap-2 justify-center">{{ $epic->begining }} → {{ $epic->end }}</p>
                        <div class="flex flex-col gap-3 flex-1">
                            @forelse($epic->tasks as $task)
                                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-3 text-sm flex flex-col gap-2 hover:shadow-md transition-shadow">
                                    <div class="font-semibold text-secondary">{{ $task->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $task->description }}</div>
                                    <div class="flex flex-col gap-2">
                                        <!-- Dropdown statut -->
                                        <div wire:ignore.self class="relative">
                                            <button class="px-2 py-1 rounded text-xs font-semibold text-white w-full
                                                    {{ $task->statut === 'terminé' ? 'bg-green-500' : ($task->statut === 'en cours' ? 'bg-yellow-500' : 'bg-gray-500') }}"
                                                    wire:click="$set('showStatutDropdown.{{ $task->id }}', true)">
                                                <i class="bi bi-exclamation-circle-fill mr-1"></i>
                                                {{ ucfirst($task->statut) }}
                                                <i class="bi bi-chevron-down ml-1"></i>
                                            </button>
                                            @if(isset($showStatutDropdown[$task->id]) && $showStatutDropdown[$task->id])
                                            <div class="absolute mt-1 bg-white border border-gray-200 rounded shadow-md w-28 z-10">
                                                @foreach(['à faire', 'en cours', 'terminé'] as $statut)
                                                    <button type="button"
                                                        wire:click="updateTask({{ $task->id }}, 'statut', '{{ $statut }}')"
                                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100 text-sm">
                                                        {{ ucfirst($statut) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <!-- Dropdown priorité -->
                                        <div wire:ignore.self class="relative">
                                            <button class="px-2 py-1 rounded text-xs font-semibold w-full
                                                    {{ $task->priorite === 'haute' ? 'bg-red-500 text-white' : ($task->priorite === 'moyenne' ? 'bg-orange-400 text-white' : 'bg-gray-200 text-gray-700') }}"
                                                    wire:click="$set('showPrioriteDropdown.{{ $task->id }}', true)">
                                                <i class="bi bi-arrow-up-circle-fill mr-1"></i>
                                                {{ ucfirst($task->priorite) }}
                                                <i class="bi bi-chevron-down ml-1"></i>
                                            </button>
                                            @if(isset($showPrioriteDropdown[$task->id]) && $showPrioriteDropdown[$task->id])
                                            <div class="absolute mt-1 bg-white border border-gray-200 rounded shadow-md w-28 z-10">
                                                @foreach(['basse', 'moyenne', 'haute'] as $priorite)
                                                    <button type="button"
                                                        wire:click="updateTask({{ $task->id }}, 'priorite', '{{ $priorite }}')"
                                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100 text-sm">
                                                        {{ ucfirst($priorite) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">Échéance : {{ $task->echeance }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-gray-400 italic text-xs">Aucune tâche</div>
                            @endforelse
                        </div>
                        <div class="mt-4 p-2 text-sm text-secondary bg-gray-100 border border-dashed border-gray-300 rounded shadow transition-colors duration-200 group hover:bg-gray-200">
                            <a href="{{ route('projects.sprints.epics.tasks.create', [
                                'project' => $epic->project_id,
                                'sprint' => $epic->sprint_id,
                                'epic' => $epic->id]) }}">
                                <i class="bi bi-plus-circle-fill mr-1"></i>
                                Ajouter une tâche à {{ $epic->nom }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @php
        $kanban = $this->kanbanColumns();
        $kanbanColors = [
            'à faire' => 'border-t-4 border-gray-500 bg-gray-50',
            'en cours' => 'border-t-4 border-yellow-500 bg-yellow-50',
            'terminé' => 'border-t-4 border-green-500 bg-green-50',
        ];
        $kanbanNames = [
            'à faire' => 'À faire',
            'en cours' => 'En cours',
            'terminé' => 'Terminé',
        ];
        $kanbanIcons = [
            'à faire' => 'bi-pencil-fill text-gray-600',
            'en cours' => 'bi-gear-fill text-yellow-700',
            'terminé' => 'bi-check-circle-fill text-green-700',
        ];
        $kanbanTitleColors = [
            'à faire' => 'text-gray-700',
            'en cours' => 'text-yellow-700',
            'terminé' => 'text-green-700',
        ];
    @endphp

    {{-- Colonnes Kanban à droite --}}
    <div class="flex gap-6 min-w-[54rem] flex-shrink-0">
        @foreach(['à faire', 'en cours', 'terminé'] as $statut)
            <div class="min-w-[18rem] flex flex-col shadow-md p-4 rounded-lg h-full {{ $kanbanColors[$statut] }}">
                <h2 class="font-bold text-lg {{ $kanbanTitleColors[$statut] }} mb-4 flex items-center">
                    <i class="bi {{ $kanbanIcons[$statut] }} mr-2"></i>
                    {{ $kanbanNames[$statut] }}
                    <span class="ml-auto bg-gray-200 text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">
                        {{ $kanban[$statut]->count() }}
                    </span>
                </h2>
                <div class="flex flex-col gap-3 flex-1">
                    @forelse($kanban[$statut] as $task)
                        <div class="bg-white border border-gray-200 shadow rounded-lg p-3 mb-2 hover:shadow-lg transition-shadow">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-primary/10 text-primary border border-primary/30">
                                <i class="bi bi-bookmark-fill mr-1"></i>
                                {{ isset($task->epic) && $task->epic ? $task->epic->nom : 'Sans epic' }}
                            </span>
                            <h3 class="font-semibold text-secondary mb-2">{{ $task->nom ?? 'Sans nom' }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $task->description ?? '' }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="bi bi-calendar-event mr-1"></i> {{ $task->echeance ?? 'Non défini' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-400 italic text-sm text-center py-4">Aucune tâche</div>
                    @endforelse
                </div>
                <div class="mt-auto pt-2 text-xs text-center text-gray-400">Kanban</div>
            </div>
        @endforeach
    </div>
</div>
