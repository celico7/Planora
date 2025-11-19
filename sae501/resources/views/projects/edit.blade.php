@extends('layouts.app')

@section('title', 'Modifier le projet')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Bouton retour -->
        <div class="mb-6">
            <a href="{{ route('projects.show', $project->id) }}"
                class="inline-flex items-center text-secondary dark:text-primary border border-secondary dark:border-primary px-3 py-2 rounded-md hover:bg-secondary hover:text-white dark:hover:bg-primary dark:hover:text-white transition">
                ← Retour au projet
            </a>
        </div>

        <!-- Titre -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary mb-2">Modifier le projet</h1>
            <p class="text-gray-600 dark:text-dark-muted">Mettez à jour les informations de votre projet <span class="font-semibold text-primary">{{ $project->nom }}</span>.</p>
        </div>

        <!-- Formulaire édition -->
        <div class="bg-white dark:bg-dark-card shadow-md dark:shadow-none border dark:border-dark-border rounded-lg p-8 border-t-4 border-primary">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Une erreur est survenue :</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nom du projet -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                        Nom du projet
                    </label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $project->nom) }}" required
                        class="w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-dark-text mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" required rows="5"
                        class="w-full rounded-md border-gray-300 dark:border-dark-border bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text focus:border-primary focus:ring-primary shadow-sm">{{ old('description', $project->description) }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100 dark:border-dark-border">
                    <a href="{{ route('projects.show', $project->id) }}"
                        class="px-4 py-2 rounded-md border border-gray-300 dark:border-dark-border text-gray-600 dark:text-dark-text bg-white dark:bg-dark-hover hover:bg-gray-100 dark:hover:bg-dark-border transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-md bg-primary text-white font-semibold hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                        Mettre à jour le projet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
