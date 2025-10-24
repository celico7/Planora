@extends('layouts.app')

@section('title', "Créer un Epic")

@section('content')
<div class="max-w-xl mx-auto bg-white rounded p-6 shadow">
    <h2 class="text-2xl font-bold mb-4">Créer un nouvel Epic</h2>

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
            <button type="submit" class="btn border px-4 py-2 rounded bg-[#0cbaba] text-white">Créer</button>
        </div>
    </form>
</div>
@endsection
