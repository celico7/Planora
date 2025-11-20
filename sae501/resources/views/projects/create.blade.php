@extends('layouts.app')

@section('title', 'Créer un projet')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-3xl mx-auto">

    <div class="mb-6">
            <a href="/"
                class="inline-flex items-center text-secondary dark:text-primary border border-secondary dark:border-primary px-3 py-2 rounded-md hover:bg-secondary hover:text-white dark:hover:bg-primary dark:hover:text-white transition">
                ← Retour à l'accueil
            </a>
        </div>

        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary mb-2">Créer un nouveau projet</h1>
            <p class="text-gray-600 dark:text-dark-muted">Remplissez les informations ci-dessous pour démarrer un nouveau projet.</p>
        </div>

        <!-- Carte formulaire -->
        <div class="bg-white dark:bg-dark-card shadow-md dark:shadow-none border dark:border-dark-border rounded-lg p-8 border-t-4 border-primary">
            <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nom du projet -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                        Nom du projet
                    </label>
                    <input type="text" name="nom" id="nom"
                           value="{{ old('nom') }}"
                           required
                           placeholder="Ex: Site web e-commerce"
                           class="w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text placeholder-gray-400 dark:placeholder-dark-muted focus:border-primary focus:ring-primary shadow-sm">
                    @error('nom')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Décrivez les objectifs et les spécificités du projet..."
                              class="w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text placeholder-gray-400 dark:placeholder-dark-muted focus:border-primary focus:ring-primary shadow-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de début -->
                <div>
                    <label for="begining" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                        Date de début
                    </label>
                    <input type="date" name="begining" id="begining" value="{{ old('begining') }}"
                           class="w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm">
                    @error('begining')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100 dark:border-dark-border">
                    <a href="/"
                       class="px-4 py-2 rounded-md border border-gray-300 dark:border-dark-border text-gray-600 dark:text-dark-text bg-white dark:bg-dark-hover hover:bg-gray-100 dark:hover:bg-dark-border transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 rounded-md bg-primary text-white font-semibold hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                        Créer le projet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
