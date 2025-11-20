@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Bouton retour -->
        <div class="mb-6">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                class="inline-flex items-center text-secondary dark:text-primary border border-secondary dark:border-primary px-3 py-2 rounded-md hover:bg-secondary hover:text-white dark:hover:bg-primary dark:hover:text-white transition">
                ← Retour au sprint
            </a>
        </div>

        <!-- Titre -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary mb-2">Créer une nouvelle tâche pour {{ $epic->nom }} du sprint {{ $sprint->nom }}</h1>
            <p class="text-gray-600 dark:text-dark-muted">Ajoutez les informations nécessaires pour planifier une tâche dans le projet
                <span class="font-semibold text-primary">{{ $project->nom }}</span>.
            </p>
            <p class="text-gray-600 dark:text-dark-muted">Durée de l'epic : du {{ $epic->begining }} au {{ $epic->end }}</p>
        </div>

        <div class="bg-white dark:bg-dark-card shadow-md dark:shadow-none border dark:border-dark-border rounded-lg p-8 border-t-4 border-primary">
            <form method="POST"
                action="{{ route('projects.sprints.epics.tasks.store', [
                    'project' => $project->id,
                    'sprint' => $sprint->id,
                    'epic' => $epic->id]) }}">
                @csrf

                <div class="mb-4">
                    <label for="nom" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Nom de la tâche</label>
                    <input type="text"
                           id="nom"
                           name="nom"
                           required
                           class="form-input w-full rounded-md p-2 border border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Description</label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="form-input w-full rounded-md p-2 border border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary"></textarea>
                </div>

                <div class="mb-4">
                    <label for="statut" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Statut</label>
                    <select id="statut"
                            name="statut"
                            class="form-select w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                        <option value="à faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="echeance" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Échéance</label>
                    <input type="date"
                           id="echeance"
                           name="echeance"
                           required
                           class="form-control w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                </div>

                <div class="mb-4">
                    <label for="priorite" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Priorité</label>
                    <select id="priorite"
                            name="priorite"
                            class="form-select w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="responsable_id" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">Responsable</label>
                    <select name="responsable_id"
                            id="responsable_id"
                            class="form-control w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary">
                        @foreach($project->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4 justify-end mt-8 pt-6 border-t border-gray-100 dark:border-dark-border">
                    <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                       class="px-4 py-2 rounded-md border border-gray-300 dark:border-dark-border text-gray-600 dark:text-dark-text bg-white dark:bg-dark-hover hover:bg-gray-100 dark:hover:bg-dark-border transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 rounded-md bg-primary text-white font-semibold hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                        Créer la tâche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
