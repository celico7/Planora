@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $sprint->nom }}</h1>
    <p>Durée : du {{ $sprint->begining }} au {{ $sprint->end }}</p>

    <hr>

    <h3>Créer une nouvelle tâche</h3>
    <form method="POST" action="{{ route('tasks.store', ['project' => $project->id, 'sprint' => $sprint->id]) }}">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la tâche</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select class="form-control" id="statut" name="statut">
                <option value="à faire">À faire</option>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="echeance" class="form-label">Échéance</label>
            <input type="date" class="form-control" id="echeance" name="echeance" required>
        </div>

        <div class="mb-3">
            <label for="priorite" class="form-label">Priorité</label>
            <select class="form-control" id="priorite" name="priorite">
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="responsable_id">Responsable</label>
            <select name="responsable_id" id="responsable_id" class="form-control">
                @foreach(\App\Models\User::all() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer la tâche</button>
    </form>

</div>
@endsection
