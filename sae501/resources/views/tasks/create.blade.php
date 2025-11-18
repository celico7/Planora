@extends('layouts.app')

@section('content')


<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Bouton retour -->
        <div class="mb-6">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                class="inline-flex items-center text-secondary border border-secondary px-3 py-2 rounded-md hover:bg-secondary hover:text-white transition">
                ← Retour au sprint
            </a>
        </div>

        <!-- Titre -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary mb-2">Créer une nouvelle tâche pour {{ $epic->nom }} du sprint {{ $sprint->nom }}</h1>
            <p class="text-gray-600">Ajoutez les informations nécessaires pour planifier une tâche dans le projet
                <span class="font-semibold text-primary">{{ $project->nom }}</span>.
            </p>
            <p class="text-gray-600">Durée de l'epic : du {{ $epic->begining }} au {{ $epic->end }}</p>
        </div>


 <div class="bg-white shadow-md rounded-lg p-8 border-t-4 border-primary">

    <form method="POST"
      action="{{ route('projects.sprints.epics.tasks.store', [
          'project' => $project->id,
          'sprint' => $sprint->id,
          'epic' => $epic->id]) }}">
        @csrf
        <div class="mb-4">
            <label class="block" for="nom" class="form-label">Nom de la tâche</label>
            <input type="text" required class="form-input w-full rounded p-2 border" id="nom" name="nom" required>
        </div>

        <div class="mb-4">
            <label class="block" for="description" class="form-label">Description</label>
            <textarea class="form-input w-full rounded p-2 border" id="description" name="description"></textarea>
        </div>

        <div class="mb-4">
                <label for="statut" class="block">Statut</label>
                <select class="form-select w-full rounded" id="statut" name="statut">
                    <option value="à faire">À faire</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                </select>
        </div>

        <div class="mb-4">
            <label for="echeance" class="form-label">Échéance</label>
            <input type="date" class="form-control" id="echeance" name="echeance" required>
        </div>

        <div class="mb-4">
            <label for="priorite" class="block">Priorité</label>
            <select class="form-select w-full rounded" id="priorite" name="priorite">
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="responsable_id">Responsable</label>
            <select name="responsable_id" id="responsable_id" class="form-control">
                @foreach($project->users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-4 justify-end mt-8">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}" class="btn border px-4 py-2 rounded bg-gray-100">Annuler</a>
            <button type="submit" class="btn border px-4 py-2 rounded bg-primary text-white">Créer la tâche</button>
        </div>
    </form>

</div>
    </div>
</div>
@endsection
