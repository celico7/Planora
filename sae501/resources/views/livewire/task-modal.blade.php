<div>
@if($showModal && $taskId)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity">
    <div class="bg-white dark:bg-dark-card w-full max-w-lg rounded-lg shadow-lg dark:shadow-2xl border dark:border-dark-border p-8 relative">
        <button wire:click="closeModal" class="absolute top-3 right-4 text-2xl text-gray-400 dark:text-dark-muted hover:text-black dark:hover:text-dark-text">&times;</button>
        <h2 class="text-xl font-bold text-secondary dark:text-primary mb-4">Édition de la tâche</h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Nom</label>
                <input type="text" wire:model.defer="nom" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                @error('nom') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Description</label>
                <textarea rows="3" wire:model.defer="description" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary"></textarea>
                @error('description') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Statut + Priorité -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Statut</label>
                    <select wire:model.defer="statut" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                        <option value="à faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                    </select>
                    @error('statut') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Priorité</label>
                    <select wire:model.defer="priorite" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                    @error('priorite') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Échéance -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Échéance</label>
                <input type="date" wire:model.defer="echeance" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                @error('echeance') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Responsable -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Responsable</label>
                <select wire:model.defer="responsable_id" class="w-full rounded-lg border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                    <option value="">-- Non assigné --</option>
                    @foreach($projectUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('responsable_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between gap-3 pt-4 border-t dark:border-dark-border">
                @if($task)
                    @can('delete', $task)
                    <button type="button"
                            wire:click="deleteTask"
                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette tâche ?"
                            class="px-4 py-2 bg-red-500 dark:bg-red-600 text-white rounded-lg hover:bg-red-600 dark:hover:bg-red-700 transition font-semibold">
                        <i class="bi bi-trash mr-2"></i>Supprimer
                    </button>
                    @endcan
                @endif

                <div class="flex gap-2 ml-auto">
                    <button type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-dark-hover text-gray-700 dark:text-dark-text rounded-lg hover:bg-gray-300 dark:hover:bg-dark-border transition">
                        Annuler
                    </button>
                    <button type="button"
                            wire:click="save"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 dark:hover:bg-primary/80 transition font-semibold">
                        <i class="bi bi-check-circle mr-2"></i>Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
</div>
