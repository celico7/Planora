<div>
@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-8 relative">
        <button wire:click="closeModal" class="absolute top-3 right-4 text-2xl text-gray-400 hover:text-black">&times;</button>
        <h2 class="text-xl font-bold text-secondary mb-4">Édition de la tâche</h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                <input type="text" wire:model.defer="nom" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea rows="3" wire:model.defer="description" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Statut + Priorité -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                    <select wire:model.defer="statut" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="à faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                    </select>
                    @error('statut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priorité</label>
                    <select wire:model.defer="priorite" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                    @error('priorite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Échéance -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Échéance</label>
                <input type="date" wire:model.defer="echeance" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                @error('echeance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Responsable -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Responsable</label>
                <select wire:model.defer="responsable_id" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">-- Non assigné --</option>
                    @foreach($projectUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('responsable_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary/90">Enregistrer</button>
                <button type="button" wire:click="closeModal" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300">Annuler</button>
            </div>
        </form>
    </div>
</div>
@endif
</div>
