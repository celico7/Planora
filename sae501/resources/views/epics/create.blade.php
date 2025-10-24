@extends('layouts.app')

@section('title', "Créer un Epic")

@section('content')

<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}"
                        class="inline-flex items-center text-secondary border border-secondary px-3 py-2 rounded-md hover:bg-secondary hover:text-white transition">
                        ← Retour au sprint
                    </a>
                </div>

                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-secondary mb-2">Créer un nouvel Epic</h1>
                    <p class="text-gray-600">Remplissez les informations ci-dessous pour créer un nouvel Epic dans le projet <span class="font-semibold text-primary">{{ $project->nom }}</span> et le sprint <span class="font-semibold text-primary">{{ $sprint->nom }}</span>.</p>
                    <p class="text-gray-600">Durée du sprint : du {{ $sprint->begining }} au {{ $sprint->end }}</p>
                </div>

        <div class="bg-white shadow-md rounded-lg p-8 border-t-4 border-primary">

   <form method="POST" action="{{ route('projects.sprints.epics.store', ['project' => $project->id, 'sprint' => $sprint->id]) }}">
        @csrf
        <div class="mb-4">
            <label class="block">Nom</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required class="form-input w-full rounded p-2 border" />
        </div>
        <div class="mb-4">
            <label class="block">Description</label>
            <textarea name="description" rows="3" required class="form-input w-full rounded p-2 border">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4">
            <div class="flex gap-4">
                <div>
                    <label class="block">Début</label>
                    <input type="date" name="begining" value="{{ old('begining') }}" required class="form-input rounded border" />
                </div>
                <div>
                    <label class="block">Fin</label>
                    <input type="date" name="end" value="{{ old('end') }}" required class="form-input rounded border" />
                </div>
            </div>
        </div>
        <div class="mb-4">
            <label class="block">Statut</label>
            <select name="statut" class="form-select rounded border w-full">
                <option value="prévu">Prévu</option>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
            </select>
        </div>
        <div class="flex gap-4 justify-end mt-8">
            <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}" class="btn border px-4 py-2 rounded bg-gray-100">Annuler</a>
            <button type="submit" class="btn border px-4 py-2 rounded bg-primary text-white">Créer</button>
        </div>
    </form>
</div>
    </div>
</div>
@endsection
