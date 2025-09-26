@extends('layouts.app')

@section('content')
<h1>Créer un projet</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <br>

    <label>Description :</label>
    <textarea name="description" required></textarea>
    <br>

    <button type="submit">Créer</button>
</form>
@endsection
