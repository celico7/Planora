@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded p-6 shadow">

    <h2 class="text-2xl font-bold mb-4">Créer une nouvelle tâche pour {{ $sprint->nom }}</h2>
    <h3 class="text-lg mb-2">Durée du sprint : du {{ $sprint->begining }} au {{ $sprint->end }}</h3>

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
                @foreach(\App\Models\User::all() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-4 justify-end mt-8">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}" class="btn border px-4 py-2 rounded bg-gray-100">Annuler</a>
            <button type="submit" class="btn border px-4 py-2 rounded bg-[#0cbaba] text-white">Créer la tâche</button>
        </div>
    </form>

</div>
@endsection
