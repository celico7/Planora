<div class="flex gap-6 overflow-x-auto py-4 w-full">

    @php
        // Palette de couleurs (modifie selon préférence)
        $palette = ['#0CBABA','#380036','#F59E0B','#EF4444','#10B981','#6366F1','#EC4899','#8B5CF6','#14B8A6','#DB2777'];
        $epicColors = [];
        foreach($epics as $i => $epic) {
            $epicColors[$epic->id] = $palette[$i % count($palette)];
        }
        // Fonction utilitaire contraste (simple seuil)
        function epicTextColor($hex) {
            $hex = str_replace('#','',$hex);
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
            // luminance approximative
            $l = (0.299*$r + 0.587*$g + 0.114*$b);
            return $l > 150 ? '#111827' : '#ffffff';
        }
    @endphp

    {{-- Colonne unique pour toutes les Epics, empilées --}}
    <div class="flex flex-col gap-6 min-w-[18rem] max-w-[18rem] flex-shrink-0">
        @foreach($epics as $epic)
            @php
                $c = $epicColors[$epic->id];
                $titleColor = epicTextColor($c);
            @endphp
            <div class="bg-gray-50 rounded-lg shadow p-4 border"
                 style="border-color: {{ $c }}; box-shadow: 0 0 0 1px {{ $c }}22, 0 4px 12px -2px #00000011;">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2 cursor-pointer"
                         wire:click="toggleEpic({{ $epic->id }})">
                        <h2 class="font-bold text-lg mb-0 text-center px-3 py-1 rounded"
                            style="background: {{ $c }}; color: {{ $titleColor }};">
                            {{ $epic->nom }}
                        </h2>
                        @if($openEpicId === $epic->id)
                            <i class="bi bi-chevron-up text-2xl"></i>
                        @else
                            <i class="bi bi-chevron-down text-2xl"></i>
                        @endif
                    </div>
                    <!-- Menu 3 points Épic -->
                    @can('update', $epic)
                    <div class="relative group">
                        <button class="p-2 rounded hover:bg-gray-200"
                                onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle('hidden');">
                            <i class="bi bi-three-dots-vertical text-xl"></i>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-30 group-hover:block">
                            <form method="POST"
                                  action="{{ route('projects.sprints.epics.destroy', [
                                    'project' => $epic->project_id,
                                    'sprint' => $epic->sprint_id,
                                    'epic'    => $epic->id]) }}"
                                  onsubmit="return confirm('Supprimer cet epic ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left text-red-600 px-4 py-2">
                                    <i class="bi bi-trash mr-1"></i> Supprimer l'épic
                                </button>
                            </form>
                            <a href="{{ route('projects.sprints.epics.edit', [
                                'project' => $epic->project_id,
                                'sprint' => $epic->sprint_id,
                                'epic' => $epic->id]) }}"
                               class="w-full text-left text-gray-600 px-4 py-2 flex items-center hover:bg-gray-100">
                                <i class="bi bi-pencil mr-1"></i> Modifier l'épic
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>
                @if($openEpicId === $epic->id)
                    <div class="transition-all duration-300 ease-in-out mt-4">
                        <div class="flex flex-row justify-center gap-2 items-center mb-2">
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
                                @can('update', $epic)
                                <div class="bg-white border border-gray-200 shadow rounded-lg p-3 mb-2 hover:shadow-lg transition-shadow cursor-pointer"
                                     wire:click="openTask({{ $task->id }})">
                                    <div class="font-semibold text-secondary mb-1">{{ $task->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $task->description }}</div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <!-- Badge Epic -->
                                        <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-1 rounded"
                                              style="background: {{ $c }}; color: {{ $titleColor }}; border: 1px solid {{ $c }};">
                                            <i class="bi bi-bookmark-fill mr-1"></i>{{ $epic->nom }}
                                        </span>
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
                                        {{-- Ligne échéance + avatar responsable --}}
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">Échéance : {{ $task->echeance }}</span>
                                            @php $assignee = $task->responsable; @endphp
                                            <div class="flex items-center gap-2">
                                                @if($assignee)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background=0cbaba&color=fff"
                                                         alt="{{ $assignee->name }}"
                                                         class="w-6 h-6 rounded-full ring-2 ring-white" title="{{ $assignee->name }}">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-600"
                                                         title="Non assignée">?</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="bg-white border border-gray-200 shadow rounded-lg p-3 mb-2">
                                    <div class="font-semibold text-secondary mb-1">{{ $task->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $task->description }}</div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-1 rounded"
                                              style="background: {{ $c }}; color: {{ $titleColor }}; border: 1px solid {{ $c }};">
                                            <i class="bi bi-bookmark-fill mr-1"></i>{{ $epic->nom }}
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-semibold inline-block
                                            {{ $task->statut === 'terminé' ? 'bg-green-100 text-green-700' : ($task->statut === 'en cours' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($task->statut) }}
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-semibold inline-block
                                            {{ $task->priorite === 'haute' ? 'bg-red-100 text-red-700' : ($task->priorite === 'moyenne' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($task->priorite) }}
                                        </span>
                                        {{-- Ligne échéance + avatar responsable --}}
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">Échéance : {{ $task->echeance }}</span>
                                            @php $assignee = $task->responsable; @endphp
                                            <div class="flex items-center gap-2">
                                                @if($assignee)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background=0cbaba&color=fff"
                                                         alt="{{ $assignee->name }}"
                                                         class="w-6 h-6 rounded-full ring-2 ring-white" title="{{ $assignee->name }}">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-600"
                                                         title="Non assignée">?</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                            @empty
                                <div class="text-gray-400 italic text-xs">Aucune tâche</div>
                            @endforelse
                        </div>
                        @can('update', $epic)
                        <div class="mt-4 p-2 text-sm text-secondary bg-gray-100 border border-dashed border-gray-300 rounded shadow transition-colors duration-200 group hover:bg-gray-200">
                            <a href="{{ route('projects.sprints.epics.tasks.create', [
                                'project' => $epic->project_id,
                                'sprint' => $epic->sprint_id,
                                'epic' => $epic->id]) }}">
                                <i class="bi bi-plus-circle-fill mr-1"></i>
                                Ajouter une tâche à {{ $epic->nom }}
                            </a>
                        </div>
                        @endcan
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
                        @php
                            $epicId = isset($task->epic) && $task->epic ? $task->epic->id : null;
                            $badgeColor = $epicId ? $epicColors[$epicId] : '#e5e7eb';
                            $badgeText = $epicId ? epicTextColor($badgeColor) : '#374151';
                        @endphp

                        @can('update', $task->epic)
                        <div class="bg-white border border-gray-200 shadow rounded-lg p-3 mb-2 hover:shadow-lg transition-shadow cursor-pointer"
                             wire:click="openTask({{ $task->id }})">
                            <span class="inline-block px-2 py-1 rounded text-[10px] font-semibold tracking-wide"
                                  style="background: {{ $badgeColor }}; color: {{ $badgeText }};
                                         border: 1px solid {{ $badgeColor }};">
                                <i class="bi bi-bookmark-fill mr-1"></i>
                                {{ isset($task->epic) && $task->epic ? $task->epic->nom : 'Sans epic' }}
                            </span>
                            <h3 class="font-semibold text-secondary mb-2 mt-2">{{ $task->nom ?? 'Sans nom' }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $task->description ?? '' }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="bi bi-calendar-event mr-1"></i> {{ $task->echeance ?? 'Non défini' }}
                            </div>
                            <div class="flex items-center justify-end mt-2">
                                @php $assignee = $task->responsable; @endphp
                                @if($assignee)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background=0cbaba&color=fff"
                                         alt="{{ $assignee->name }}"
                                         class="w-6 h-6 rounded-full ring-2 ring-white" title="{{ $assignee->name }}">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-600"
                                         title="Non assignée">?</div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="bg-white border border-gray-200 shadow rounded-lg p-3 mb-2">
                            <span class="inline-block px-2 py-1 rounded text-[10px] font-semibold tracking-wide"
                                  style="background: {{ $badgeColor }}; color: {{ $badgeText }};
                                         border: 1px solid {{ $badgeColor }};">
                                <i class="bi bi-bookmark-fill mr-1"></i>
                                {{ isset($task->epic) && $task->epic ? $task->epic->nom : 'Sans epic' }}
                            </span>
                            <h3 class="font-semibold text-secondary mb-2 mt-2">{{ $task->nom ?? 'Sans nom' }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $task->description ?? '' }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="bi bi-calendar-event mr-1"></i> {{ $task->echeance ?? 'Non défini' }}
                            </div>
                            <div class="flex items-center justify-end mt-2">
                                @php $assignee = $task->responsable; @endphp
                                @if($assignee)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background=0cbaba&color=fff"
                                         alt="{{ $assignee->name }}"
                                         class="w-6 h-6 rounded-full ring-2 ring-white" title="{{ $assignee->name }}">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-600"
                                         title="Non assignée">?</div>
                                @endif
                            </div>
                        </div>
                        @endcan
                    @empty
                        <div class="text-gray-400 italic text-sm text-center py-4">Aucune tâche</div>
                    @endforelse
                </div>
                <div class="mt-auto pt-2 text-xs text-center text-gray-400">Kanban</div>
            </div>
        @endforeach
    </div>
    @livewire('task-modal')
</div>
