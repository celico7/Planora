<div class="flex gap-6 overflow-x-auto py-4 w-full p-8 rounded-lg bg-gray-200 dark:bg-dark-bg">

    @php
        // Palette de couleurs
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

    {{-- Colonne unique pour toutes les Epics --}}
    <div class="flex flex-col gap-6 min-w-[18rem] max-w-[18rem] flex-shrink-0">
        @foreach($epics as $epic)
            @php
                $c = $epicColors[$epic->id];
                $titleColor = epicTextColor($c);
            @endphp
            <div class="bg-gray-50 dark:bg-dark-card rounded-lg shadow dark:shadow-none p-4 border dark:border-dark-border"
                 style="border-color: {{ $c }}; box-shadow: 0 0 0 1px {{ $c }}22, 0 4px 12px -2px #00000011;">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2 cursor-pointer"
                         wire:click="toggleEpic({{ $epic->id }})">
                        <h2 class="font-bold text-lg mb-0 text-center px-3 py-1 rounded"
                            style="background: {{ $c }}; color: {{ $titleColor }};">
                            {{ $epic->nom }}
                        </h2>
                        @if($openEpicId === $epic->id)
                            <i class="bi bi-chevron-up text-2xl text-gray-700 dark:text-dark-text"></i>
                        @else
                            <i class="bi bi-chevron-down text-2xl text-gray-700 dark:text-dark-text"></i>
                        @endif
                    </div>
                    <!-- Menu Épic -->
                    @can('update', $epic)
                    <div class="relative group">
                        <button class="p-2 rounded hover:bg-gray-200 dark:hover:bg-dark-hover"
                                onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle('hidden');">
                            <i class="bi bi-three-dots-vertical text-xl text-gray-700 dark:text-dark-text"></i>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded shadow-lg z-30 group-hover:block">
                            <form method="POST"
                                  action="{{ route('projects.sprints.epics.destroy', [
                                    'project' => $epic->project_id,
                                    'sprint' => $epic->sprint_id,
                                    'epic'    => $epic->id]) }}"
                                  onsubmit="return confirm('Supprimer cet epic ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left text-red-600 dark:text-red-400 px-4 py-2 hover:bg-gray-100 dark:hover:bg-dark-hover">
                                    <i class="bi bi-trash mr-1"></i> Supprimer l'épic
                                </button>
                            </form>
                            <a href="{{ route('projects.sprints.epics.edit', [
                                'project' => $epic->project_id,
                                'sprint' => $epic->sprint_id,
                                'epic' => $epic->id]) }}"
                               class="w-full text-left text-gray-600 dark:text-dark-text px-4 py-2 flex items-center hover:bg-gray-100 dark:hover:bg-dark-hover">
                                <i class="bi bi-pencil mr-1"></i> Modifier l'épic
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>
                @if($openEpicId === $epic->id)
                    <div class="transition-all duration-300 ease-in-out mt-4">
                        <div class="flex flex-row justify-center gap-2 items-center mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-200 dark:bg-dark-hover text-xs font-medium text-gray-700 dark:text-dark-text">
                                <i class="bi bi-list-check mr-1"></i>{{ $epic->tasks->count() }} tâches
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-green-200 dark:bg-green-900/30 text-xs font-medium text-green-800 dark:text-green-400">
                                <i class="bi bi-check-circle-fill mr-1"></i>{{ $epic->tasks->where('statut', 'terminé')->count() }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 dark:bg-yellow-900/30 text-xs font-medium text-yellow-800 dark:text-yellow-400">
                                <i class="bi bi-gear-fill mr-1"></i>{{ $epic->tasks->where('statut', 'en cours')->count() }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-300">
                                <i class="bi bi-pencil mr-1"></i>{{ $epic->tasks->where('statut', 'à faire')->count() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-dark-muted my-3 text-center">{{ $epic->description }}</p>
                        <p class="mb-4 font-semibold text-gray-400 dark:text-dark-muted flex gap-2 justify-center">{{ $epic->begining }} → {{ $epic->end }}</p>
                        <div class="flex flex-col gap-3 flex-1">
                            @forelse($epic->tasks as $task)
                                @can('update', $epic)
                                <div class="bg-white dark:bg-dark-hover border border-gray-200 dark:border-dark-border shadow dark:shadow-none rounded-lg p-3 mb-2 hover:shadow-lg dark:hover:shadow-xl transition-shadow cursor-pointer"
                                     wire:click="openTask({{ $task->id }})" data-task-id="{{ $task->id }}">
                                    <div class="font-semibold text-secondary dark:text-primary mb-1">{{ $task->nom }}</div>
                                    <div class="text-xs text-gray-500 dark:text-dark-muted">{{ $task->description }}</div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <!-- Badge Epic -->
                                        <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-1 rounded"
                                              style="background: {{ $c }}; color: {{ $titleColor }}; border: 1px solid {{ $c }};">
                                            <i class="bi bi-bookmark-fill mr-1"></i>{{ $epic->nom }}
                                        </span>
                                        <!-- Dropdown statut -->
                                        <div wire:ignore.self class="relative">
                                            <button class="px-2 py-1 rounded text-xs font-semibold text-white w-full
                                                    {{ $task->statut === 'terminé' ? 'bg-green-500 dark:bg-green-600' : ($task->statut === 'en cours' ? 'bg-yellow-500 dark:bg-yellow-600' : 'bg-gray-500 dark:bg-gray-600') }}"
                                                    wire:click.stop="$set('showStatutDropdown.{{ $task->id }}', true)">
                                                <i class="bi bi-exclamation-circle-fill mr-1"></i>
                                                {{ ucfirst($task->statut) }}
                                                <i class="bi bi-chevron-down ml-1"></i>
                                            </button>
                                            @if(isset($showStatutDropdown[$task->id]) && $showStatutDropdown[$task->id])
                                            <div class="absolute mt-1 bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded shadow-md w-28 z-10"
                                                 wire:click.stop>
                                                @foreach(['à faire', 'en cours', 'terminé'] as $statut)
                                                    <button type="button"
                                                        wire:click="updateTask({{ $task->id }}, 'statut', '{{ $statut }}')"
                                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100 dark:hover:bg-dark-hover text-sm text-gray-700 dark:text-dark-text">
                                                        {{ ucfirst($statut) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <!-- Dropdown priorité -->
                                        <div wire:ignore.self class="relative">
                                            <button class="px-2 py-1 rounded text-xs font-semibold w-full
                                                    {{ $task->priorite === 'haute' ? 'bg-red-500 dark:bg-red-600 text-white' : ($task->priorite === 'moyenne' ? 'bg-orange-400 dark:bg-orange-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300') }}"
                                                    wire:click.stop="$set('showPrioriteDropdown.{{ $task->id }}', true)">
                                                <i class="bi bi-arrow-up-circle-fill mr-1"></i>
                                                {{ ucfirst($task->priorite) }}
                                                <i class="bi bi-chevron-down ml-1"></i>
                                            </button>
                                            @if(isset($showPrioriteDropdown[$task->id]) && $showPrioriteDropdown[$task->id])
                                            <div class="absolute mt-1 bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded shadow-md w-28 z-10"
                                                 wire:click.stop>
                                                @foreach(['basse', 'moyenne', 'haute'] as $priorite)
                                                    <button type="button"
                                                        wire:click="updateTask({{ $task->id }}, 'priorite', '{{ $priorite }}')"
                                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100 dark:hover:bg-dark-hover text-sm text-gray-700 dark:text-dark-text">
                                                        {{ ucfirst($priorite) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        {{-- Ligne échéance + avatar responsable --}}
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500 dark:text-dark-muted">Échéance : {{ $task->echeance ? $task->echeance->format('d/m/Y') : 'Non définie' }}</span>
                                            @php $assignee = $task->assignee; @endphp
                                            <div class="flex items-center gap-2">
                                                @if($assignee)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background={{ $assignee->avatar_color ?? '0cbaba' }}&color=fff"
                                                         alt="{{ $assignee->name }}"
                                                         class="w-6 h-6 rounded-full ring-2 ring-white dark:ring-dark-border" title="{{ $assignee->name }}">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-dark-hover flex items-center justify-center text-[10px] text-gray-600 dark:text-dark-muted"
                                                         title="Non assignée">?</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="bg-white dark:bg-dark-hover border border-gray-200 dark:border-dark-border shadow dark:shadow-none rounded-lg p-3 mb-2">
                                    <div class="font-semibold text-secondary dark:text-primary mb-1">{{ $task->nom }}</div>
                                    <div class="text-xs text-gray-500 dark:text-dark-muted">{{ $task->description }}</div>
                                    <div class="flex flex-col gap-2 mt-2">
                                        <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-1 rounded"
                                              style="background: {{ $c }}; color: {{ $titleColor }}; border: 1px solid {{ $c }};">
                                            <i class="bi bi-bookmark-fill mr-1"></i>{{ $epic->nom }}
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-semibold inline-block
                                            {{ $task->statut === 'terminé' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : ($task->statut === 'en cours' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300') }}">
                                            {{ ucfirst($task->statut) }}
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-semibold inline-block
                                            {{ $task->priorite === 'haute' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : ($task->priorite === 'moyenne' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300') }}">
                                            {{ ucfirst($task->priorite) }}
                                        </span>
                                        {{-- Ligne échéance + avatar responsable --}}
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500 dark:text-dark-muted">Échéance : {{ $task->echeance ? $task->echeance->format('d/m/Y') : 'Non définie' }}</span>
                                            @php $assignee = $task->assignee; @endphp
                                            <div class="flex items-center gap-2">
                                                @if($assignee)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background={{ $assignee->avatar_color ?? '0cbaba' }}&color=fff"
                                                         alt="{{ $assignee->name }}"
                                                         class="w-6 h-6 rounded-full ring-2 ring-white dark:ring-dark-border" title="{{ $assignee->name }}">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-dark-hover flex items-center justify-center text-[10px] text-gray-600 dark:text-dark-muted"
                                                         title="Non assignée">?</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                            @empty
                                <div class="text-gray-400 dark:text-dark-muted italic text-xs">Aucune tâche</div>
                            @endforelse
                        </div>
                        @can('update', $epic)
                        <div class="mt-4 p-2 text-sm text-secondary dark:text-primary bg-gray-100 dark:bg-dark-hover border border-dashed border-gray-300 dark:border-dark-border rounded shadow transition-colors duration-200 group hover:bg-gray-200 dark:hover:bg-dark-border">
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
            'à faire' => 'border-t-4 border-gray-500 bg-gray-50 dark:bg-dark-card',
            'en cours' => 'border-t-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10',
            'terminé' => 'border-t-4 border-green-500 bg-green-50 dark:bg-green-900/10',
        ];
        $kanbanNames = [
            'à faire' => 'À faire',
            'en cours' => 'En cours',
            'terminé' => 'Terminé',
        ];
        $kanbanIcons = [
            'à faire' => 'bi-pencil-fill text-gray-600 dark:text-gray-400',
            'en cours' => 'bi-gear-fill text-yellow-700 dark:text-yellow-500',
            'terminé' => 'bi-check-circle-fill text-green-700 dark:text-green-500',
        ];
        $kanbanTitleColors = [
            'à faire' => 'text-gray-700 dark:text-gray-300',
            'en cours' => 'text-yellow-700 dark:text-yellow-400',
            'terminé' => 'text-green-700 dark:text-green-400',
        ];
    @endphp

    {{-- Colonnes Kanban à droite --}}
    <div class="flex gap-6 min-w-[54rem] flex-shrink-0">
        @foreach(['à faire', 'en cours', 'terminé'] as $statut)
            <div class="flex-1 flex flex-col shadow-md dark:shadow-none p-4 rounded-lg h-full {{ $kanbanColors[$statut] }} border dark:border-dark-border kanban-column" data-status="{{ $statut }}">
                <h2 class="font-bold text-lg {{ $kanbanTitleColors[$statut] }} mb-4 flex items-center">
                    <i class="bi {{ $kanbanIcons[$statut] }} mr-2"></i>
                    {{ $kanbanNames[$statut] }}
                    <span class="ml-auto bg-gray-200 dark:bg-dark-hover text-gray-700 dark:text-dark-text text-xs font-semibold px-2 py-1 rounded-full">
                        {{ $kanban[$statut]->count() }}
                    </span>
                </h2>
                <div class="flex flex-col gap-3 flex-1 kanban-tasks-container">
                    @forelse($kanban[$statut] as $task)
                        @php
                            $epicId = isset($task->epic) && $task->epic ? $task->epic->id : null;
                            $badgeColor = $epicId ? $epicColors[$epicId] : '#e5e7eb';
                            $badgeText = $epicId ? epicTextColor($badgeColor) : '#374151';
                        @endphp

                        @can('update', $task->epic)
                        <div class="bg-white dark:bg-dark-hover border border-gray-200 dark:border-dark-border shadow dark:shadow-none rounded-lg p-3 mb-2 hover:shadow-lg dark:hover:shadow-xl transition-shadow"
                             data-task-id="{{ $task->id }}">
                            <div class="flex items-start gap-2">
                                <i class="bi bi-grip-vertical cursor-grab text-gray-400 dark:text-gray-500 flex-shrink-0 mt-1"></i>
                                <div class="flex-1 cursor-pointer" wire:click="openTask({{ $task->id }})">
                                    <span class="inline-block px-2 py-1 rounded text-[10px] font-semibold tracking-wide"
                                          style="background: {{ $badgeColor }}; color: {{ $badgeText }};
                                                 border: 1px solid {{ $badgeColor }};">
                                        <i class="bi bi-bookmark-fill mr-1"></i>
                                        {{ isset($task->epic) && $task->epic ? $task->epic->nom : 'Sans epic' }}
                                    </span>
                                    <h3 class="font-semibold text-secondary dark:text-primary mb-2 mt-2">{{ $task->nom ?? 'Sans nom' }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-dark-muted mb-3">{{ $task->description ?? '' }}</p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-dark-muted">
                                        <i class="bi bi-calendar-event mr-1"></i> {{ $task->echeance ? $task->echeance->format('d/m/Y') : 'Non définie' }}
                                    </div>
                                    <div class="flex items-center justify-end mt-2">
                                        @php $assignee = $task->assignee; @endphp
                                        @if($assignee)
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background={{ $assignee->avatar_color ?? '0cbaba' }}&color=fff"
                                                 alt="{{ $assignee->name }}"
                                                 class="w-6 h-6 rounded-full ring-2 ring-white dark:ring-dark-border" title="{{ $assignee->name }}">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-dark-hover flex items-center justify-center text-[10px] text-gray-600 dark:text-dark-muted"
                                                 title="Non assignée">?</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-white dark:bg-dark-hover border border-gray-200 dark:border-dark-border shadow dark:shadow-none rounded-lg p-3 mb-2">
                            <span class="inline-block px-2 py-1 rounded text-[10px] font-semibold tracking-wide"
                                  style="background: {{ $badgeColor }}; color: {{ $badgeText }};
                                         border: 1px solid {{ $badgeColor }};">
                                <i class="bi bi-bookmark-fill mr-1"></i>
                                {{ isset($task->epic) && $task->epic ? $task->epic->nom : 'Sans epic' }}
                            </span>
                            <h3 class="font-semibold text-secondary dark:text-primary mb-2 mt-2">{{ $task->nom ?? 'Sans nom' }}</h3>
                            <p class="text-xs text-gray-500 dark:text-dark-muted mb-3">{{ $task->description ?? '' }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-dark-muted">
                                <i class="bi bi-calendar-event mr-1"></i> {{ $task->echeance ? $task->echeance->format('d/m/Y') : 'Non définie' }}
                            </div>
                            <div class="flex items-center justify-end mt-2">
                                @php $assignee = $task->assignee; @endphp
                                @if($assignee)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background={{ $assignee->avatar_color ?? '0cbaba' }}&color=fff"
                                         alt="{{ $assignee->name }}"
                                         class="w-6 h-6 rounded-full ring-2 ring-white dark:ring-dark-border" title="{{ $assignee->name }}">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-dark-hover flex items-center justify-center text-[10px] text-gray-600 dark:text-dark-muted"
                                         title="Non assignée">?</div>
                                @endif
                            </div>
                        </div>
                        @endcan
                    @empty
                        <div class="text-gray-400 dark:text-dark-muted italic text-sm text-center py-4">Aucune tâche</div>
                    @endforelse
                </div>
                <div class="mt-auto pt-2 text-xs text-center text-gray-400 dark:text-dark-muted">Kanban</div>
            </div>
        @endforeach
    </div>
    @livewire('task-modal')
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kanbanColumns = document.querySelectorAll('.kanban-tasks-container');

        kanbanColumns.forEach(container => {
            new Sortable(container, {
                group: 'kanban',
                animation: 150,
                handle: '.bi-grip-vertical',
                ghostClass: 'opacity-50',
                dragClass: 'opacity-75',
                onEnd: function (evt) {
                    const taskId = evt.item.dataset.taskId;
                    const newStatus = evt.to.closest('[data-status]').dataset.status;

                    fetch(`/tasks/${taskId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            @this.call('$refresh');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour du statut', error);
                        evt.item.remove();
                        evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                    });
                }
            });
        });
    });
</script>
