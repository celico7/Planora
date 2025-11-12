<div>
@if($showModal && $task)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-8 relative">
        <button wire:click="closeModal" class="absolute top-3 right-4 text-2xl text-gray-400 hover:text-black">&times;</button>
        <h2 class="text-xl font-bold text-secondary mb-4">Édition de la tâche</h2>
        <form wire:submit.prevent="updateTask" class="space-y-4">
            <div>
                <label for="nom" class="block text-sm font-semibold mb-1">Nom</label>
                <input type="text" wire:model.defer="editData.nom" class="w-full rounded border"/>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Description</label>
                <textarea wire:model.defer="editData.description" class="w-full rounded border"></textarea>
            </div>
            <div class="flex gap-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Statut</label>
                    <select wire:model.defer="editData.statut" class="rounded border">
                        <option value="à faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Priorité</label>
                    <select wire:model.defer="editData.priorite" class="rounded border">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Échéance</label>
                <input type="date" wire:model.defer="editData.echeance" class="rounded border"/>
            </div>
            {{-- Champ Responsable --}}
            <div class="mt-4">
                <label for="responsable_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                <select id="responsable_id"
                        class="mt-1 block w-full rounded-md border-gray-300"
                        wire:model.defer="editData.responsable_id">
                    <option value="">Non assignée</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">
                            {{ $member->name }} ({{ $member->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-row justify-between gap-2 pt-4 border-t">
                <button wire:click="deleteTask" type="button"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Supprimer</button>
                <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endif
</div>
