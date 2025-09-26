@extends('layouts.app')

@section('content')
<h1>Modifier le projet</h1>

<form action="{{ route('projects.update', $project->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <label>Nom :</label>
    <input type="text" name="nom" value="{{ $project->nom }}" required>
    <br>

    <label>Description :</label>
    <textarea name="description" required>{{ $project->description }}</textarea>
    <br>

    <button type="submit">Mettre à jour</button>
</form>
@endsection
