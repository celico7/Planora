@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Bouton retour -->
    <a href="{{ route('projects.index', $project) }}" class="btn btn-secondary mb-3">
        ← Retour au projet
    </a>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">➕ Créer un nouveau sprint pour <strong>{{ $project->nom }}</strong></h4>
        </div>

        <div class="card-body">
            <!-- Affichage des erreurs -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire -->
            <form action="{{ route('sprints.store', $project) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du sprint</label>
                    <input
                        type="text"
                        name="nom"
                        id="nom"
                        class="form-control"
                        placeholder="Ex: Sprint 1 - Développement initial"
                        value="{{ old('nom') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="begining" class="form-label">Date de début</label>
                    <input
                        type="date"
                        name="begining"
                        id="begining"
                        class="form-control"
                        value="{{ old('begining') }}"
                        required
                    >
                </div>

                <div>
                    <label for="end" class="form-label">Date de fin</label>
                    <input
                        type="date"
                        name="end"
                        id="end"
                        class="form-control"
                        value="{{ old('end') }}"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3">
                    🚀 Créer le sprint
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
