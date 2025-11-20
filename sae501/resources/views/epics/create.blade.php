@extends('layouts.app')

@section('title', "Créer un Epic")

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                class="inline-flex items-center text-secondary dark:text-primary border border-secondary dark:border-primary px-3 py-2 rounded-md hover:bg-secondary hover:text-white dark:hover:bg-primary dark:hover:text-white transition">
                ← Retour au sprint
            </a>
        </div>

        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary mb-2">Créer un nouvel Epic</h1>
            <p class="text-gray-600 dark:text-dark-muted">Remplissez les informations ci-dessous pour créer un nouvel Epic dans le projet <span class="font-semibold text-primary">{{ $project->nom }}</span> et le sprint <span class="font-semibold text-primary">{{ $sprint->nom }}</span>.</p>
            <p class="text-gray-600 dark:text-dark-muted">Durée du sprint : du {{ $sprint->begining }} au {{ $sprint->end }}</p>
        </div>

        <div class="bg-white dark:bg-dark-card shadow-md dark:shadow-none border dark:border-dark-border rounded-lg p-8 border-t-4 border-primary">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Erreurs :</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.sprints.epics.store', ['project' => $project->id, 'sprint' => $sprint->id]) }}" class="space-y-6">
                @csrf
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Nom</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                        class="form-input w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" required
                        class="form-input w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm">{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="begining" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Début</label>
                        <input type="date" name="begining" id="begining" value="{{ old('begining') }}" required
                            class="form-input w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm" />
                    </div>
                    <div class="flex-1">
                        <label for="end" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Fin</label>
                        <input type="date" name="end" id="end" value="{{ old('end') }}" required
                            class="form-input w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm" />
                    </div>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Statut</label>
                    <select name="statut" id="statut"
                        class="form-select w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm">
                        <option value="prévu">Prévu</option>
                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                    </select>
                </div>

                <div class="flex gap-4 justify-end mt-8 pt-6 border-t border-gray-100 dark:border-dark-border">
                    <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                        class="px-4 py-2 rounded-md border border-gray-300 dark:border-dark-border text-gray-600 dark:text-dark-text bg-white dark:bg-dark-hover hover:bg-gray-100 dark:hover:bg-dark-border transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-md bg-primary text-white font-semibold hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
