<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    @php
        $columns = [
            'a faire' => ['color' => 'gray', 'icon' => 'bi-pencil-fill'],
            'en cours' => ['color' => 'yellow', 'icon' => 'bi-gear-fill'],
            'termine' => ['color' => 'green', 'icon' => 'bi-check-circle-fill'],
        ];
    @endphp

    @foreach($columns as $status => $data)
        <div class="bg-{{ $data['color'] }}-50 rounded-lg shadow-md p-4 border-t-4 border-{{ $data['color'] }}-500 flex flex-col">
            <h2 class="font-bold text-lg text-{{ $data['color'] }}-700 mb-4 flex items-center">
                <i class="bi {{ $data['icon'] }} mr-2"></i>
                {{ ucfirst($status) }}
                <span class="ml-auto bg-{{ $data['color'] }}-200 text-{{ $data['color'] }}-800 text-xs font-semibold px-2 py-1 rounded-full">
                    {{ $tasks[$status]->count() }}
                </span>
            </h2>

            <div class="flex flex-col gap-3 overflow-y-auto max-h-[70vh] pr-2">
                @forelse($tasks[$status] as $task)
                    <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-3 hover:shadow-lg transition-shadow">

                        <!-- Épic -->
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-primary/10 text-primary border border-primary/30">
                                <i class="bi bi-bookmark-fill mr-1"></i>
                                {{ $task->epic?->nom ?? 'Sans epic' }}
                            </span>
                        </div>

                        <!-- Nom + Description -->
                        <h3 class="font-semibold text-secondary mb-1">{{ $task->nom }}</h3>
                        <p class="text-xs text-gray-500 mb-3">{{ $task->description }}</p>

                        <!-- Dropdowns -->
                        <div class="flex flex-wrap gap-2 items-center">
                            <!-- Statut -->
                            <div wire:ignore.self class="relative">
                                <button class="px-2 py-1 rounded text-xs font-semibold text-white
                                    {{ $task->statut === 'termine' ? 'bg-green-500' :
                                       ($task->statut === 'en cours' ? 'bg-yellow-500' : 'bg-gray-500') }}"
                                    wire:click="$set('showStatutDropdown.{{ $task->id }}', true)">
                                    {{ ucfirst($task->statut ?? 'Indéfini') }}
                                    <i class="bi bi-chevron-down ml-1"></i>
                                </button>

                                @if(isset($showStatutDropdown[$task->id]) && $showStatutDropdown[$task->id])
                                    <div class="absolute mt-1 bg-white border border-gray-200 rounded shadow-md w-28 z-10">
                                        @foreach(['a faire', 'en cours', 'termine'] as $s)
                                            <button type="button"
                                                    wire:click="updateTask({{ $task->id }}, 'statut', '{{ $s }}')"
                                                    class="block w-full text-left px-3 py-1 hover:bg-gray-100 text-sm">
                                                {{ ucfirst($s) }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Priorité -->
                            <div wire:ignore.self class="relative">
                                <button class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $task->priorite === 'haute' ? 'bg-red-500 text-white' :
                                       ($task->priorite === 'moyenne' ? 'bg-orange-400 text-white' : 'bg-gray-200 text-gray-700') }}"
                                    wire:click="$set('showPrioriteDropdown.{{ $task->id }}', true)">
                                    <i class="bi bi-arrow-up-circle-fill mr-1"></i>{{ ucfirst($task->priorite ?? 'Indéfinie') }}
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

                            <!-- Échéance -->
                            <div class="ml-auto text-xs text-gray-500 flex items-center">
                                <i class="bi bi-calendar-event mr-1"></i>
                                {{ $task->echeance ?? 'Non défini' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400 italic text-sm text-center py-4">Aucune tâche</div>
                @endforelse
            </div>
        </div>
    @endforeach

</div>
