<div class="max-w-7xl mx-auto">
    <!-- Titre -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-secondary mb-2">
            <i class="bi bi-search mr-2"></i>Recherche de tâches
        </h1>
        <p class="text-gray-600">Filtrez et recherchez vos tâches à travers tous vos projets</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6" wire:key="filters-wrap-{{ $filtersKey }}">
        <div class="flex flex-wrap gap-4 mb-3">

            <!-- Mot-clé -->
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-semibold text-secondary mb-2">
                    <i class="bi bi-search mr-1"></i>Recherche
                </label>
                <input type="text"
                       wire:model.debounce.300ms.live="search"
                       placeholder="Nom ou description..."
                       class="w-full px-4 py-2 border border-secondary rounded-xl focus:ring-2 focus:ring-primary">
            </div>

            <!-- Filtre Statut -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-secondary mb-2">
                    <i class="bi bi-gear-fill mr-1"></i>Statut
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full px-4 py-2 border border-secondary rounded-xl focus:ring-2 focus:ring-primary">
                    <option value="">Tous les statuts</option>
                    <option value="à faire">À faire</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                </select>
            </div>

            <!-- Filtre Priorité -->
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold text-secondary mb-2">
                    <i class="bi bi-exclamation-circle-fill mr-1"></i>Priorité
                </label>
                <select wire:model.live="priorityFilter"
                        class="w-full px-4 py-2 border border-secondary rounded-xl focus:ring-2 focus:ring-primary">
                    <option value="">Toutes les priorités</option>
                    <option value="basse">Basse</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                </select>
            </div>

            <!-- Filtre Responsable -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-secondary mb-2">
                    <i class="bi bi-person-fill mr-1"></i>Responsable
                </label>
                <select wire:model.live="responsableFilter"
                        class="w-full px-4 py-2 border border-secondary rounded-xl focus:ring-2 focus:ring-primary">
                    <option value="">Tous les responsables</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Date de fin -->
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold text-secondary mb-2">
                    <i class="bi bi-calendar-event mr-1"></i>Date de fin
                </label>
                <input type="date"
                       wire:model.live="dateTo"
                       class="w-full px-4 py-2 border border-secondary rounded-xl focus:ring-2 focus:ring-primary">
            </div>
        </div>

        <div class="flex justify-between items-center mt-2">
            <span class="text-sm text-gray-400 italic">
                @if($count ?? false)
                    {{ $count }} tâche(s) trouvée(s)
                @endif
            </span>
            <button type="button" wire:click="resetFilters"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                <i class="bi bi-x-circle mr-1"></i>Réinitialiser les filtres
            </button>
        </div>
    </div>


    <!-- Résultats -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                Résultats ({{ $tasks->total() }})
            </h2>
        </div>

        @if($tasks->count() > 0)
            <div class="space-y-4">
                @foreach($tasks as $task)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Titre et badges -->
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="font-bold text-lg text-secondary">{{ $task->nom }}</h3>

                                    <!-- Badge Statut -->
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $task->statut === 'terminé' ? 'bg-green-100 text-green-700' :
                                           ($task->statut === 'en cours' ? 'bg-yellow-100 text-yellow-700' :
                                           'bg-gray-100 text-gray-700') }}">
                                        <i class="bi bi-{{ $task->statut === 'terminé' ? 'check-circle-fill' :
                                                           ($task->statut === 'en cours' ? 'gear-fill' : 'pencil-fill') }} mr-1"></i>
                                        {{ ucfirst($task->statut) }}
                                    </span>

                                    <!-- Badge Priorité -->
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $task->priorite === 'haute' ? 'bg-red-100 text-red-700' :
                                           ($task->priorite === 'moyenne' ? 'bg-orange-100 text-orange-700' :
                                           'bg-gray-100 text-gray-700') }}">
                                        <i class="bi bi-exclamation-circle-fill mr-1"></i>
                                        {{ ucfirst($task->priorite) }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-3">{{ $task->description }}</p>

                                <!-- Métadonnées -->
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <!-- Projet > Sprint > Epic -->
                                    @if($task->epic && $task->epic->sprint && $task->epic->sprint->project)
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-folder-fill text-primary"></i>
                                            <span>{{ $task->epic->sprint->project->nom }}</span>
                                            <i class="bi bi-chevron-right text-xs"></i>
                                            <span>{{ $task->epic->sprint->nom }}</span>
                                            <i class="bi bi-chevron-right text-xs"></i>
                                            <span class="font-medium">{{ $task->epic->nom }}</span>
                                        </div>
                                    @endif

                                    <!-- Échéance -->
                                    <div class="flex items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        <span>{{ \Carbon\Carbon::parse($task->echeance)->format('d/m/Y') }}</span>
                                    </div>

                                    <!-- Responsable -->
                                    @if($task->responsable)
                                        <div class="flex items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($task->responsable->name) }}&background={{ $task->responsable->avatar_color ?? '0cbaba' }}&color=fff"
                                                 alt="{{ $task->responsable->name }}"
                                                 class="w-6 h-6 rounded-full ring-2 ring-white">
                                            <span>{{ $task->responsable->name }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1 text-gray-400">
                                            <i class="bi bi-person-x"></i>
                                            <span>Non assignée</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bouton voir -->
                            @if($task->epic && $task->epic->sprint && $task->epic->sprint->project)
                                <a href="{{ route('projects.sprints.show', ['project' => $task->epic->sprint->project->id, 'sprint' => $task->epic->sprint->id]) }}"
                                   class="ml-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#089a8f] transition">
                                    <i class="bi bi-eye mr-1"></i>Voir
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-search text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune tâche trouvée</p>
                <p class="text-gray-400 text-sm">Essayez de modifier vos filtres de recherche</p>
            </div>
        @endif
    </div>
</div>
