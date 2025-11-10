@extends('layouts.app')

@section('title', 'Modifier l\'Epic')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Bouton retour -->
        <div class="mb-6">
            <a href="{{ route('projects.sprints.show', [$project->id, $sprint->id]) }}"
                class="inline-flex items-center text-secondary border border-secondary px-3 py-2 rounded-md hover:bg-secondary hover:text-white transition">
                ← Retour au sprint
            </a>
        </div>

        <!-- Titre -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary mb-2">Modifier l'Epic</h1>
            <p class="text-gray-600">Mettez à jour les informations de cet epic dans le sprint <span class="font-semibold text-primary">{{ $sprint->nom ?? 'n/a' }}</span>.</p>
        </div>

        <!-- Formulaire d'édition -->
        <div class="bg-white shadow-md rounded-lg p-8 border-t-4 border-primary">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-800 border border-red-200 rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Une erreur est survenue :</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.sprints.epics.update', [$project->id, $sprint->id, $epic->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom de l'epic</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $epic->nom) }}" required
                        class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" required rows="4"
                        class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary shadow-sm">{{ old('description', $epic->description) }}</textarea>
                </div>

                <!-- Dates -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="begining" class="block text-sm font-semibold text-gray-700 mb-2">Début</label>
                        <input type="date" name="begining" id="begining" value="{{ old('begining', $epic->begining) }}" required
                            class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                    <div class="flex-1">
                        <label for="end" class="block text-sm font-semibold text-gray-700 mb-2">Fin</label>
                        <input type="date" name="end" id="end" value="{{ old('end', $epic->end) }}" required
                            class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                    <select name="statut" id="statut" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary shadow-sm">
                        <option value="prévu" {{ old('statut', $epic->statut) == 'prévu' ? 'selected' : '' }}>Prévu</option>
                        <option value="en cours" {{ old('statut', $epic->statut) == 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminé" {{ old('statut', $epic->statut) == 'terminé' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('projects.sprints.show', [$project->id, $sprint->id]) }}"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-md bg-primary text-white font-semibold hover:bg-[#089a8f] transition">
                        Mettre à jour l'epic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
